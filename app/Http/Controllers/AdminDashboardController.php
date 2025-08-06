<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

use App\Services\UserService;
use App\Services\PayoutRequestService;
use App\Services\BOKRequestService;
use App\Services\SettingsService;
use App\Services\LevelService;
use App\Services\OfferService;

class AdminDashboardController extends Controller
{
    protected UserService $user_service;
    protected PayoutRequestService $payout_request_service;
    protected BOKRequestService $bok_request_service;
    protected SettingsService $settings_service;
    protected LevelService $level_service;
    protected OfferService $offer_service;

    public function __construct(
            UserService $user_service, 
            PayoutRequestService $payout_request_service,
            BOKRequestService $bok_request_service,
            SettingsService $settings_service,
            LevelService $level_service,
            OfferService $offer_service)
    {
        $this->user_service             = $user_service;
        $this->payout_request_service   = $payout_request_service;
        $this->bok_request_service      = $bok_request_service;
        $this->settings_service         = $settings_service;
        $this->level_service            = $level_service;
        $this->offer_service            = $offer_service;
    }
    
    public function index()
    {
        // dd(session('auth_mimic_uuid'));
        // dd(app() -> isDownForMaintenance());
        return Inertia::render('Admin/Home', [
            'users'                     => $this->user_service->latest(),
            'levels'                    => $this->level_service->get(),
            'latest_payout_requests'    => $this->payout_request_service->latest_incomplete(),
            'latest_bok_request'        => $this->bok_request_service->latest(),
            'last_logins'               => $this->user_service->last_logins(),
            'latest_offers'             => $this->offer_service->latest(),
        ]);
    }
}
