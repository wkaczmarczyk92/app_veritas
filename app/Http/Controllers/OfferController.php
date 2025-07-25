<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Offer;
use Inertia\Inertia;

use Exception;

use App\Services\CRM\OfferService;
use App\Services\CRM\RecruiterService;
// use App\Models\Language;
use App\Models\Land;
use App\Models\CRMPatientWakingUp;
use App\Models\CRMPatientMobility;
use App\Models\CRM\Language;
use Illuminate\Support\Facades\Mail;
use App\Mail\OfferEmail;
use App\Helpers\CURLRequest;
use App\Helpers\Response;
use Illuminate\Support\Facades\DB;
use App\Helpers\Transaction;


class OfferController extends Controller
{
    protected OfferService $offer_service;
    protected RecruiterService $recruiter_service;

    public function __construct(OfferService $offer_service, RecruiterService $recruiter_service)
    {
        $this->offer_service = $offer_service;
        $this->recruiter_service = $recruiter_service;
    }

    public function index()
    {
        $offers = Offer::with('user.user_profiles')
            ->orderBy('created_at', 'desc')
            ->paginate(50);

        // dd($offers[3]->user->user_profiles->full_name);

        return Inertia::render('Admin/Offers', [
            'offers' => $offers
        ]);
    }

    public function destroy($id)
    {
        return Transaction::try(
            function () use ($id) {
                $offer = Offer::find($id);
                $offer->delete();
            },
            'Oferta została usunięta.'
        );
    }

    public function store(Request $request)
    {
        try {
            DB::beginTransaction();

            $user = Auth::user();
            $user->load('user_profiles');

            $offer = Offer::where('user_id', $user->id)
                ->where('crm_offer_id', $request->pnr_id_planer)
                ->whereDate('created_at', '>=', date('Y-m-d'))
                ->firstOrNew([
                    'user_id' => $user->id,
                    'crm_offer_id' => $request->pnr_id_planer,
                    'hp_code' => $request->family['fml_code_HP'],
                    'start_date' => $request->pnr_start_date,
                    'crm_family_id' => $request->family['fml_id_family']
                ]);

            if (!$offer->exists) {
                try {
                    $offer->save();
                } catch (Exception $e) {
                    return Response::danger('Coś poszło nie tak. Spróbuj ponownie później.', e: $e);
                }
            }


            $curl_request = new CURLRequest;
            $curl_request->send_caretaker_that_applied_for_the_offer($request, $user);

            if (app()->environment() == 'production') {
                $email_data = [
                    'first_name' => $user->user_profiles->first_name,
                    'last_name' => $user->user_profiles->last_name,
                    'offers' => [
                        $offer
                    ],
                    'caretaker_crm_url' => 'https://local.grupa-veritas.pl/#/opiekunki/' . $user->user_profiles->crt_id_caretaker,
                    'caretaker_app_url' => 'https://app.veritas.pl/uzytkownik/' . $user->id,
                    'has_crm_account' => $user->user_profiles->crt_id_caretaker == null
                ];

                if ($user->user_profiles != null && $user->user_profiles->crt_id_user_recruiter != null) {
                    $recruiter_service = new RecruiterService();
                    $recruiter = $recruiter_service->get($user->user_profiles->crt_id_user_recruiter);

                    OfferEmail::$email = $recruiter->usr_email;
                }

                // OfferEmail::$email = 'w.kaczmarczyk@grupa-veritas.pl';
                Mail::to(OfferEmail::$email)->send(
                    new OfferEmail($email_data)
                );
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return Response::danger('Coś poszło nie tak. Spróbuj ponownie później.', e: $e);
        }

        return Response::success('Zgłoszenie zostało przyjęte.', [
            'offer_id' => $offer->crm_offer_id
        ]);


        // return response()->json([
        //     'success' => true,
        //     'msg' => 'Zgłoszenie zostało przyjęte.',
        //     'alert_type' => 'success',
        //     '
        // ]);
    }

    public function user_offers(Request $request)
    {
        $user = Auth::user();
        $user->load([
            'ready_to_departure_dates',
            'user_profiles',
            'user_profiles.crm_profile',
            'user_profiles.crm_profile.caretaker_planer_data'
        ]);

        // dd($user->ready_to_departure_dates);

        $filters = [];
        $user_offers_id = Offer::where('user_id', $user->id)->get()->pluck('crm_offer_id');
        $todays_offers_count = Offer::whereDate('created_at', date('Y-m-d'))->where('user_id', auth()->id())->count();

        // dd($todays_offers_count);


        // $user_with_crm_account = $user->user_profiles->crt_id_caretaker;
        // $layout = $user_with_crm_account ? 'user' : 'free_account';

        // dd($user);

        return Inertia::render('User/Offer/Offers', [
            'user' => $user,
            'lands' => Land::all(),
            'languages' => Language::all(),
            'filters' => $filters,
            'user_offers_id' => $user_offers_id,
            'waking_up' => CRMPatientWakingUp::all(),
            'mobilities' => CRMPatientMobility::all(),
            // 'layout' => $layout
            'todays_offers_count' => $todays_offers_count
        ]);
    }

    public function index_free_account()
    {
        $filters = [];
        $user_offers_id = Offer::where('user_id', auth()->id())->get()->pluck('crm_offer_id');
        // dd($user_offers);

        $todays_offers_count = Offer::whereDate('created_at', date('Y-m-d'))->where('user_id', auth()->id())->count();


        return Inertia::render('User/Offer/Offer.index.free.account', [
            'lands' => Land::all(),
            'languages' => Language::all(),
            'filters' => $filters,
            'user_offers_id' => $user_offers_id,
            'todays_offers_count' => $todays_offers_count
        ]);
    }

    public function my_offers_free_account()
    {
        $user = Auth::user();
        $user->load([
            'user_profiles',
        ]);

        $user_with_crm_account = $user->user_profiles->crt_id_caretaker;
        $layout = $user_with_crm_account ? 'user' : 'free_account';

        $filters = [];
        $offers = Offer::with([
            'planer',
            'planer.family',
            'planer.family.patient',
            'planer.family.patient.mobility',
            'planer.family.patient.waking_up',
            'planer.family.address',
        ])->where('user_id', auth()->id())->whereDate('created_at', date('Y-m-d'))->get();
        // $todays_offers_count = Offer::whereDate('created_at', date('Y-m-d'))->count();

        return Inertia::render('User/Offer/My.offer.free.account', [
            'offers' => $offers,
            'filters' => $filters,
            'lands' => Land::all(),
            'languages' => Language::all(),
            'layout' => $layout
            // 'todays_offers_count' => $todays_offers_count
        ]);
    }
}
