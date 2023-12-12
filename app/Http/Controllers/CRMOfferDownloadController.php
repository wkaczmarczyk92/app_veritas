<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\GermanZipCode;
use App\Models\CRMPlaner;
use App\Models\CRMCaretaker;

use Illuminate\Validation\ValidationException;
use App\Http\Requests\CRMOfferDownloadRequest;

use Illuminate\Support\Facades\Auth;
use App\Models\User;

use App\Models\CRMRecruiter;

use App\Services\CRM\OfferService;

class CRMOfferDownloadController extends Controller
{
    public function download(CRMOfferDownloadRequest $request, OfferService $offer_service) {
        return response()->json($offer_service->download(Auth::user(), $request->lands));
    }
}
