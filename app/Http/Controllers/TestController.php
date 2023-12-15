<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Exception;
use Illuminate\Support\Facades\Mail;
use App\Mail\BOKEmail;
use App\Mail\TestMail;
// use Illuminate\Support\Facades\Request;

use Inertia\Inertia;
use Illuminate\Support\Facades\Artisan;
use Throwable;

use Illuminate\Support\Str;

use App\Helpers\SMS;
use App\Console\Commands\CRONSendSMSWithPassword;

use App\Models\User;
use App\Models\UserProfile;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
// use Exception;

use App\Models\UserHasBonus;
use Illuminate\Support\Facades\DB;
use App\Helpers\CURLRequest;
use App\Models\UserPoint;
use App\Models\PointCheckpoint;
use App\Models\LevelBonusValue;


use App\Models\CRMAssignment;
use App\Models\CRMCaretaker;
use App\Models\CRMCompany;

use App\Models\CRMFamilies;
use App\Models\CRMPlaner;
// use Illuminate\Foundation\Auth\User;

use App\Models\GermanZipCode;
use PDO;
// use CRMC

use Illuminate\Support\Facades\Auth;
use App\Services\CRM\RecruiterService;
use App\Services\MailService;
use App\Models\Offer;

class TestController extends Controller
{
    protected RecruiterService $recruiter_service;
    protected MailService $mail_service;

    public function __construct(RecruiterService $recruiter_service, MailService $mail_service) {
        $this->recruiter_service = $recruiter_service;
        $this->mail_service = $mail_service;
    }

    public function email_offer() {
        $users = User::with(['user_profiles', 'offers'])
            ->whereHas('offers', function($query) {
                $query->where('notification_send', false);
            })->get();

        $this->mail_service->offer_email($users, $this->recruiter_service);
        $offer_ids = $users->pluck('offers.*.id')->flatten();
        Offer::whereIn('id', $offer_ids)->update(['notification_send' => true]);
    }

    public function manual_update() {
        set_time_limit(0);

        $current_date = date('Y-m-d');
        $year = date('Y', strtotime($current_date . " -1 month"));
        $month = date('m', strtotime($current_date . " -1 month"));
        // $year = '2023';
        // $month = '07';

        $curl_request = new CURLRequest;

        // // możliwe że trzeba podzielić - po 5k użytkowników
        $users = User::with(['user_profiles.levels.checkpoints', 'user_profiles.levels.multiplier', 'user_has_bonus'])
            ->role('user')
            ->get();

        $pesels = [];

        foreach ($users as $user) {
            $pesels[] = $user->pesel;
        }

        $response = $curl_request->get_user_worked_days_in_previous_month($pesels, $year, $month);

        DB::beginTransaction();

        try {
            foreach ($response as $pesel => $days) {
                $user = User::with(['user_profiles.levels.checkpoints', 'user_profiles.levels.multiplier', 'user_has_bonus'])
                    ->where('pesel', $pesel)
                    ->first();

                if ($user->user_profiles->level >= 4) {
                    continue;
                }

                $point_checkpoint = PointCheckpoint::all();

                $user->user_profiles->total_days += $days;
                $user->user_profiles->total_points += $days;
                $user->user_profiles->current_points += $days;

                $current_user_level_id = $user->user_profiles->level;

                foreach ($point_checkpoint as $checkpoint) {
                    if ($checkpoint->checkpoint <= $user->user_profiles->total_points) {
                        $user->user_profiles->level = $checkpoint->level_id;

                        if ($current_user_level_id < $user->user_profiles->level) {
                            $user_has_bonuse = UserHasBonus::create([
                                'user_id' => $user->id,
                                'level_id' => $user->user_profiles->level,
                                'bonus_value' => LevelBonusValue::where('level_id', $user->user_profiles->level)->pluck('value')[0]
                            ]);
                        }
                    }
                }

                $user_point = UserPoint::create([
                    'user_id' => $user->id,
                    'points' => $days,
                    'days' => $days,
                    'auto' => true,
                    'type' => 1
                ]);

                $user->user_profiles->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            echo json_encode([
                'success' => false,
                'msg' => '',
                'exception' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'msg' => 'Udało się.',
            'exception' => ''
        ]);
    }

    public function svg_test() {
        return Inertia::render('Test');
    }


    public function offers() {
        $user = Auth::user();
        $user->load('user_profiles', 'ready_to_departure_dates');
        // dd($user->ready_to_departure_dates->departure_date);
        // $user = Auth::user();
        // $user->load('user_profiles');
        // dd($user);
        // // pobieranie id jezyka opiekunki
        $caretaker = CRMCaretaker::with(['data' => function($query) {
            $query->select('crt_id_data_caretaker', 'crt_id_language');
        }])
        ->where('crt_id_caretaker', 21)
        ->has('data')
        ->orderBy('crt_id_caretaker')
        // ->first()
        ->get();

        dd($caretaker->first());

        // $language_id = $caretaker->pluck('data.crt_id_language')[0];

        // // pobranie wszystkich kodów pocztowych landu
        // $zip_code = GermanZipCode::where('land_id', 4)->pluck('zip_code');

        // // pobranie ofert dla opiekunki
        // $planer = CRMPlaner::with(['family', 'family.address'])
        //     ->whereIn('pnr_id_status', [1, 11])
        //     ->whereHas('family.address', function($query) use ($zip_code) {
        //         $query->whereIn('adr_postcode', $zip_code->toArray());
        //     })
        //     ->where('pnr_start_date', '>=', '2023-12-01')
        //     ->where('pnr_id_rate_language', $language_id)
        //     ->orderBy('pnr_id_planer', 'desc')
        //     ->limit(10)
        //     ->get();
    }


    public function mowgli() {
        $users = User::whereNotNull('pesel')->get();

        foreach ($users as $user) {
            echo $user->pesel . '<br>';
        }

        // foreach ($users as $user) {
        //     try {
        //         $caretaker = CRMCaretaker::with('crm_assignments.crm_company', function($query) {
        //             $query->where('cmp_id_company_group', [1, 2]);
        //         })->get();

        //         if ($caretaker) {
        //             $d[] = $caretaker;
        //         }
        //     } catch (Exception $e) {
        //         echo $e->getMessage();
        //     }

        // }

        // var_dump($d);



    }





    public function overdueCRON() {
        set_time_limit(0);
        return;
        $current_date = date('Y-m-d');
        // $year = date('Y', strtotime($current_date . " -1 month"));
        // $month = date('m', strtotime($current_date . " -1 month"));
        $year = '2023';
        $month = '01';

        $curl_request = new CURLRequest;

        // // możliwe że trzeba podzielić - po 5k użytkowników
        $users = User::with(['user_profiles.levels.checkpoints', 'user_profiles.levels.multiplier', 'user_has_bonus'])
            ->role('user')
            ->get();

        $pesels = [];

        foreach ($users as $user) {
            $pesels[] = $user->pesel;
        }

        $response = $curl_request->get_user_worked_days_in_previous_month($pesels, $year, $month);

        DB::beginTransaction();

        try {
            foreach ($response as $pesel => $days) {
                $user = User::with(['user_profiles.levels.checkpoints', 'user_profiles.levels.multiplier', 'user_has_bonus'])
                    ->where('pesel', $pesel)
                    ->first();

                if ($user->user_profiles->level >= 4) {
                    continue;
                }

                $point_checkpoint = PointCheckpoint::all();

                $user->user_profiles->total_days += $days;
                $user->user_profiles->total_points += $days;
                $user->user_profiles->current_points += $days;

                $current_user_level_id = $user->user_profiles->level;

                foreach ($point_checkpoint as $checkpoint) {
                    if ($checkpoint->checkpoint <= $user->user_profiles->total_points) {
                        $user->user_profiles->level = $checkpoint->level_id;

                        if ($current_user_level_id < $user->user_profiles->level) {
                            $user_has_bonuse = UserHasBonus::create([
                                'user_id' => $user->id,
                                'level_id' => $user->user_profiles->level,
                                'bonus_value' => LevelBonusValue::where('level_id', $user->user_profiles->level)->pluck('value')[0]
                            ]);
                        }
                    }
                }

                $user_point = UserPoint::create([
                    'user_id' => $user->id,
                    'points' => $days,
                    'days' => $days,
                    'auto' => true,
                    'type' => 1
                ]);

                $user->user_profiles->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            echo json_encode([
                'success' => false,
                'msg' => '',
                'exception' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile()
            ]);
            return;
        }

        echo json_encode([
            'success' => true,
            'msg' => 'Udało się.',
            'exception' => ''
        ]);
    }

    public function createUser() {
        $user = User::create([
            'email' => 'i.salamonowicz@grupa-veritas.pl',
            'password' => Hash::make('!;:>%eC*Zy_9WF4F'),
        ]);

        $user->assignRole('admin');

        $user_profile = new UserProfile([
            'first_name' => 'Iza',
            'last_name' => 'Salamonowicz',
            'level' => null,
        ]);

        $user->user_profiles()->save($user_profile);
        return;

        // Krystian Soroka
        $user = User::create([
            'email' => 'k.soroka@grupa-veritas.pl',
            'password' => Hash::make('123Krystian!@#'),
        ]);

        $user->assignRole('super-admin');

        $user_profile = new UserProfile([
            'first_name' => 'Krystian',
            'last_name' => 'Soroka',
            'level' => null,
        ]);

        $user->user_profiles()->save($user_profile);

        // Adam Pniewski
        unset($user);
        $user = User::create([
            'email' => 'a.pniewski@grupa-veritas.pl',
            'password' => Hash::make('123Adam!@#'),
        ]);

        $user->assignRole('admin');

        $user_profile = new UserProfile([
            'first_name' => 'Adam',
            'last_name' => 'Pniewski',
            'level' => null,
        ]);

        $user->user_profiles()->save($user_profile);

        // Dorota Kołoszewska
        unset($user);
        $user = User::create([
            'email' => 'd.koloszewska@grupa-veritas.pl',
            'password' => Hash::make('X,JzWh=<2#v{+/*2'),
        ]);

        $user->assignRole('admin');

        $user_profile = new UserProfile([
            'first_name' => 'Dorota',
            'last_name' => 'Kołoszewska',
            'level' => null,
        ]);

        $user->user_profiles()->save($user_profile);
    }



    public function sms_test() {
        $cron = new CRONSendSMSWithPassword;
        $cron->handle();
    }

    public function mail_test()
    {
        Mail::to('wojciech.kaczmarczyk11@gmail.com')->send(
            new TestMail()
        );

    }

    public function php_version() {
        phpinfo();
    }

    public function command() {
        return Inertia::render('Command');
    }

    public function callCommand(Request $request) {
        if ($request->command_type == 'artisan') {


            // Artisan::call('down', ['--allow' => '79.110.201.17']);
            Artisan::call($request->artisan_call);
            return response()->json([
                'msg' => Artisan::output()
            ]);
        } else {
            $arr = explode(' ', $request->class_call);
            $exception = '';
            $response = 'success';
            try {
                $class = new $arr[0];
                $response = $class->{$arr[1]}();
            } catch (Throwable $e) {
                $exception = $e->getMessage();
            }

            return response()->json([
                'msg' => $response,
                'exception' => $exception
            ]);
        }
    }

    public function up() {
        Artisan::call('up');
        return response()->json([
            'msg' => Artisan::output()
        ]);
    }

    public function down() {
        Artisan::call('down', ['--secret' => '1630542a-246b-4b66-afa1-dd72a4c43515']);
        return response()->json([
            'msg' => Artisan::output()
        ]);
    }



}
