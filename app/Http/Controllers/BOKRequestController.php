<?php

namespace App\Http\Controllers;

use App\Http\Requests\BOKRequest as BOKRequestForm;
use App\Models\BOKRequest;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

use App\Services\BOKRequestService;
use App\Services\MailService;
use App\Services\CRM\RecruiterService;

class BOKRequestController extends Controller
{
    protected MailService $mail_service;
    protected BOKRequestService $bok_request_service;
    protected RecruiterService $recruiter_service;

    public function __construct(
        MailService $mail_service,
        BOKRequestService $bok_request_service,
        RecruiterService $recruiter_service,
    ) {
        $this->mail_service = $mail_service;
        $this->bok_request_service = $bok_request_service;
        $this->recruiter_service = $recruiter_service;
    }

    public function index()
    {
        $bok_reuqests = BOKRequest::with(['user.user_profiles', 'subject', 'bank_account', 'bank_account.account_type'])
            ->orderBy('created_at', 'desc')
            ->paginate(5);

        return Inertia::render('Admin/BOKRequests', [
            'bok_requests' => $bok_reuqests,
        ]);
    }

    public function store(BOKRequestForm $request)
    {
        return response()->json(
            $this->bok_request_service->store(
                $request->all(),
                Auth::user(),
                $this->mail_service,
                $this->recruiter_service,
            )
        );
    }
}
