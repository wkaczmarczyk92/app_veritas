<?php

namespace App\Http\Controllers\Testing;

use App\Http\Controllers\Controller;

use App\Exports\PayoutRequestsExport;
use Maatwebsite\Excel\Facades\Excel;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\UserHasBonus;

use App\Models\BonusStatus;
use App\Services\CRM\DocumentService;

use App\Helpers\CaretakerAgreementGetter;

use Illuminate\Support\Facades\Log;

use Illuminate\Support\Facades\DB;

use Exception;

use Illuminate\Support\Facades\Mail;
use App\Mail\ExportPayoutRequestsEmail;


class ExcelTestController extends Controller
{
    public $alert_rows = [];

    public function export()
    {
        Log::build([
            'driver' => 'single',
            'path' => storage_path('logs/laravel_222.log'),
        ])->info('smth happened');
        return;

        // dd(Storage::disk('exports')->url('20240514_zlecenia_wyplaty_bonusow.xlsx'));

        set_time_limit(0);

        $user_has_bonuses = UserHasBonus::with([
            'user' => function ($query) {
                $query->select('id', 'pesel');
            },
            'user.user_profiles' => function ($query) {
                $query->select('user_id', 'first_name', 'last_name', 'phone_number', 'crt_id_caretaker');
            }
        ])
            ->where('bonus_status_id', BonusStatus::where('name', 'in_progress')->value('id'))
            ->get(['user_id', 'bonus_value']);

        $data = [];
        $i = 0;

        DB::beginTransaction();

        try {
            foreach ($user_has_bonuses as $user) {
                $caretaker_agreement_getter = new CaretakerAgreementGetter;

                $data[$i]['no'] = $i + 1;
                $data[$i]['pesel'] = $user->user->pesel;
                $data[$i]['first_name'] = $user->user->user_profiles->first_name;
                $data[$i]['last_name'] = $user->user->user_profiles->last_name;
                $data[$i]['phone_number'] = $user->user->user_profiles->phone_number;
                $data[$i]['bonus_value'] = $user->bonus_value . ' €';
                $data[$i]['company_name'] = $caretaker_agreement_getter->get($user->user->user_profiles->crt_id_caretaker);
                $i++;

                // $user->bonus_status_id = BonusStatus::where('name', 'completed')->value('id');
                // $user->save();
            }

            usort($data, function ($a, $b) {
                return $a['company_name'] <=> $b['company_name'];
            });

            $filename = date('Ymd') . "_zlecenia_wyplaty_bonusow.xlsx";

            Excel::store(new PayoutRequestsExport($data, $i + 1, $this->alert_rows), $filename, 'exports');

            if (app()->environment('production')) {
                Mail::to(ExportPayoutRequestsEmail::$emails)
                    ->cc(ExportPayoutRequestsEmail::$cc_emails)
                    ->send(new ExportPayoutRequestsEmail($filename));
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            Log::alert('Błąd podczas generowania pliku zlecenia wypłat bonusów. 
            Exception:' . $e->getMessage() . '
            Linia: ' . $e->getLine() . '
            Plik: ' . $e->getFile());
            return;
        }

        Log::info('Plik został wygenerowany. Nazwa pliku: ' . $filename);
        return;
    }
}
