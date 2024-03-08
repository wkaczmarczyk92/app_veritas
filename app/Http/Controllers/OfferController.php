<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use App\Models\Offer;
use Inertia\Inertia;

use Exception;

use App\Services\CRM\OfferService;
use App\Services\CRM\RecruiterService;

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

        return Inertia::render('Admin/Offers', [
            'offers' => $offers
        ]);
    }

    public function store(Request $request)
    {
        $user = Auth::user();

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
                return response()->json([
                    'success' => false,
                    'msg' => 'Coś poszło nie tak.',
                    'alert_type' => 'danger',
                    'exception' => $e->getMessage()
                ]);
            }
        }

        $user = Auth::user();
        $user->load('user_profiles');

        if (app()->environment('production')) {
            $email_data = [
                'first_name' => $user->user_profiles->first_name,
                'last_name' => $user->user_profiles->last_name,
                'offers' => [
                    $offer
                ],
                'caretaker_crm_url' => 'https://local.grupa-veritas.pl/#/opiekunki/' . $user->user_profiles->crt_id_caretaker,
                'caretaker_app_url' => 'http://app.veritas.pl/uzytkownik/' . $user->id,
            ];

            // OfferEmail::$email = 'w.kaczmarczyk@grupa-veritas.pl';
            // Mail::to(OfferEmail::$email)->send(
            //     new OfferEmail($email_data)
            // );
        }

        return response()->json([
            'success' => true,
            'msg' => 'Zgłoszenie zostało przyjęte.',
            'alert_type' => 'success'
        ]);
    }

    public function user_offers()
    {
        // dd('kurwa mac');
        $user = Auth::user();
        $user->load('ready_to_departure_dates');

        // dd($user);

        return Inertia::render('User/Offer/Offers', [
            'user' => $user
        ]);
    }
}
