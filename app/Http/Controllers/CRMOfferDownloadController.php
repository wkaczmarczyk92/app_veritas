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
    public function download(CRMOfferDownloadRequest $request, OfferService $offer_service)
    {
        return response()->json($offer_service->download(Auth::user(), $request->lands));
    }

    public function download_no_crm(Request $request)
    {
        // dd($request->all());

        $filters = [];

        $offers = CRMPlaner::with([
            'family',
            'family.patient',
            'family.patient.mobility',
            'family.patient.waking_up',
            'family.address'
        ])
            ->has('family.patient')
            ->where('pnr_confirmed', 1)
            ->whereIn('pnr_id_status', [1, 11]);

        if (!empty($request->date)) {
            $start_date = date('Y-m-d', strtotime($request->date[0]));
            $end_date = date('Y-m-d', strtotime($request->date[1]));

            $filters['date'][0] = $start_date;
            $filters['date'][1] = $end_date;

            $offers = $offers->where('pnr_start_date', '>=', $start_date);
            $offers = $offers->where('pnr_start_date', '<=', $end_date);
        }

        if (!empty($request->salary)) {
            $filters['salary'] = $request->salary;

            $offers = $offers->where('pnr_caretaker_rate', '>=', $request->salary);
        }

        if (!empty($request->lands_id)) {
            $zip_code = GermanZipCode::whereIn('land_id', $request->lands_id)->pluck('zip_code');

            $offers = $offers->whereHas('family.address', function ($query) use ($zip_code) {
                $query->whereIn('adr_postcode', $zip_code->toArray());
            });

            $filters['lands_id'] = $request->lands_id;
        }

        if (!empty($request->languages_id)) {
            $offers = $offers->whereIn('pnr_id_rate_language', $request->languages_id);
            $filters['languages_id'] = $request->languages_id;
        }

        if (!empty($request->waking_up_id)) {
            $offers = $offers->whereHas('family.patient.waking_up', function ($query) use ($request) {
                $query->whereIn('fwu_id', $request->waking_up_id);
            });
            $filters['waking_up_id'] = $request->waking_up_id;
        }

        if (!empty($request->mobility_id)) {
            $offers = $offers->whereHas('family.patient.mobility', function ($query) use ($request) {
                $query->whereIn('plm_id', $request->mobility_id);
            });
            $filters['mobility_id'] = $request->mobility_id;
        }

        $offers = $offers->where('pnr_caretaker_rate', '<=', 2200)
            ->whereRaw('pnr_caretaker_rate + 400 <= pnr_family_rate')
            ->orderBy('pnr_start_date', 'asc')
            ->get();



        // dd($start_date, $end_date);


        return response()->json([
            'offers' => $offers
        ]);
    }
}
