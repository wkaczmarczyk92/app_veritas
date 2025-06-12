<?php

namespace App\Services\Caretaker;

use Illuminate\Support\Facades\DB;

use App\Services\Service;

use App\Models\CaretakerRecommendation;
use App\Models\Language;

use App\Helpers\CURLRequest;
use App\Helpers\Response;

use Illuminate\Support\Facades\Auth;

class CaretakerRecommendationService extends Service
{
    public function ov_get($search = '')
    {
        $data = CaretakerRecommendation::with(['user.user_profiles', 'admin_user.user_profiles']);

        if (isset($search) and $search != '') {
            $data->where('id', '=', $search)
                ->orWhere('caretaker_first_name', 'like', "%{$search}%")
                ->orWhere('caretaker_last_name', 'like', "%{$search}%")
                ->orWhere('caretaker_email', 'like', "%{$search}%")
                ->orWhere('caretaker_phone_number', 'like', "%{$search}%")
                ->orWhereHas('user.user_profiles', function ($query) use ($search) {
                    $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhereRaw("CONCAT(first_name, ' ', last_name) like ?", ["%{$search}%"])
                        ->orWhereRaw("CONCAT(last_name, ' ', first_name) like ?", ["%{$search}%"]);
                });
        }

        return $data->orderBy('created_at', 'desc')->paginate(50);
    }

    public function store($user_id)
    {
        try {
            CaretakerRecommendation::create([
                'user_id' => $user_id
            ]);
        } catch (\Exception $e) {
            return Response::danger('');
        }

        return Response::success('');
    }

    public function update($data, $id)
    {
        DB::beginTransaction();

        $msg = 'Wystąpił błąd podczas połączenia. Spróbuj ponownie później.';

        try {
            $caretaker = $this->find($id, class: \App\Models\CaretakerRecommendation::class);

            $caretaker->caretaker_first_name    = $data['caretaker_first_name'];
            $caretaker->caretaker_last_name     = $data['caretaker_last_name'];
            $caretaker->caretaker_email         = $data['caretaker_email'];
            $caretaker->caretaker_phone_number  = $data['caretaker_phone_number'];
            $caretaker->language_id             = $data['language_id'];
            $caretaker->locked                  = true;
            $caretaker->updated_by_user_id      = Auth::user()->id;

            if (app()->environment('production')) {
                $curl_request = new CURLRequest;
                $arr = [
                    'first_name'    => $caretaker->caretaker_first_name,
                    'last_name'     => $caretaker->caretaker_last_name,
                    'language'      => Language::where('id', '=', $caretaker->language_id)->pluck('name')[0],
                    'phone_number'  => $caretaker->caretaker_phone_number
                ];

                $response = $curl_request->send_new_caretaker_recommendation_to_leads($arr);

                // dd($response);

                if (!$response->success) {
                    throw new \Exception($response->msg . ' - CRM');
                }
            }

            $caretaker->crm_lead_id = $response->result->lead_id ?? 1;
            $caretaker->save();

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            return Response::danger($e->getMessage(), [
                'exception' => $e->getMessage()
            ]);
        }

        return Response::success('Dane opiekunki zostały zaktualizowane i przekazane do CC.');
    }

    public function destroy($id)
    {
        try {
            $caretaker = $this->find($id, class: \App\Models\CaretakerRecommendation::class);
            $caretaker->delete();
        } catch (\Exception $e) {
            return Response::danger('Wystąpił błąd podczas połączenia. Spróbuj ponownie później.', [
                'exception' => $e->getMessage()
            ]);
        }

        return Response::success('Opiekunka zostala usunięta. Za chwilę zostaniesz przekierowany...');
    }
}
