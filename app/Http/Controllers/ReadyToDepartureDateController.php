<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\ReadyToDepartureDate;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use App\Models\User;
use App\Models\UserProfile;

// use App\Http\Controllers\HomePageController;
// use Auth
// use Aut

use Illuminate\Support\Facades\DB;

use Illuminate\Validation\ValidationException;
use App\Helpers\CURLRequest;
use Exception;
use App\Services\CRM\RecruiterService;

use App\Mail\RedyToDepartureDateEmail;
use Illuminate\Support\Facades\Mail;

class ReadyToDepartureDateController extends Controller
{
    /**
     * Store a newly created resource in storage.
     */
    public function storeOrUpdate(Request $request)
    {
        try {
            $validation = $request->validate([
                'departure_date' => 'required|date|date_format:Y-m-d|after:today'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ]);
        }

        DB::beginTransaction();
        $user_id = Auth::user()->id;
        $user = User::with('user_profiles')->find($user_id);

        try {
            $ready_to_departure = ReadyToDepartureDate::updateOrCreate([
                'user_id' => $user_id
            ], [
                'departure_date' => $request->departure_date
            ]);

            $crt_id_caretaker = UserProfile::where('user_id', '=', $user_id)->pluck('crt_id_caretaker')[0];

            if ($crt_id_caretaker) {
                $curl_request = new CURLRequest;
                $departure_response = $curl_request->caretaker_departure_date($request->departure_date, $crt_id_caretaker);

                if (!$departure_response->success) {
                    throw new Exception('CRM Update failed.');
                }

                if (app()->environment('production')) {
                    $email_data = [
                        'date' => $request->departure_date,
                        'username' => $user->user_profiles->first_name . ' ' . $user->user_profiles->last_name,
                        'url' => "http://app.veritas.pl/uzytkownik/{$user->id}",
                        'url_crm' => "https://local.grupa-veritas.pl/#/opiekunki/{$user->user_profiles->crt_id_caretaker}"
                    ];

                    // Mail::to('wojciech.kaczmarczyk11@gmail.com')->send(
                    //     new RedyToDepartureDateEmail($email_data)
                    // );

                    $recruiter_service = new RecruiterService;
                    $recruiter = $recruiter_service->get($user->user_profiles->crt_id_user_recruiter);

                    if (!empty($recruiter->usr_email) and filter_var($recruiter->usr_email, FILTER_VALIDATE_EMAIL)) {
                        Mail::to($recruiter->usr_email)->send(
                            new RedyToDepartureDateEmail($email_data)
                        );
                    }
                }
            }

            DB::commit();
        } catch (Exception $th) {
            DB::rollback();
            return response()->json([
                'success' => 'false',
                'alert_type' => 'danger',
                'msg' => 'Wystąpił błąd podczas połączenia. Spróbuj ponownie później.'
            ]);
        }

        return response()->json([
            'success' => true,
            'alert_type' => 'success',
            'msg' => 'Data została zaktualizowana. Nasz konsultant skontaktuje się z Tobą najszybciej jak to możliwe w godzinach pracy 8 - 17 od pon - pt',
            'ready_to_departure' => $ready_to_departure,
            'departure_response' => $departure_response ?? null
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function destroy(Request $request)
    {
        try {
            $validation = $request->validate([
                'id' => 'required'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'alert_type' => 'danger',
                'msg' => 'Wystąpił błąd podczas połączenia. Spróbuj ponownie później.'
            ]);
        }

        DB::beginTransaction();

        try {
            $record = ReadyToDepartureDate::find($request->id);

            if ($record) {
                $record->delete();
            }

            $crt_id_caretaker = UserProfile::where('user_id', '=', Auth::user()->id)->pluck('crt_id_caretaker')[0];

            if ($crt_id_caretaker) {
                $curl_request = new CURLRequest;
                $departure_response = $curl_request->caretaker_departure_date(null, $crt_id_caretaker);

                if (!$departure_response->success) {
                    throw new Exception('CRM Update failed.');
                }
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'alert_type' => 'danger',
                'msg' => 'Wystąpił błąd podczas połączenia. Spróbuj ponownie później.'
            ]);
        }

        return response()->json([
            'success' => true,
            'alert_type' => 'success',
            'msg' => 'Data gotowości do wyjazdu została pomyślnie usunięta.',
            'departure_response' => $departure_response ?? null
        ]);
    }
}
