<?php

namespace App\Services\CRM;

use App\Models\User;
use App\Models\CRMCaretaker;
use App\Models\GermanZipCode;

use App\Models\CRMPlaner;

class OfferService
{
    public function download(User $user, $lands)
    {
        $user->load('user_profiles', 'ready_to_departure_dates');

        if ($user->ready_to_departure_dates == null) {
            return [
                'success' => false,
                'msg' => 'Uzupełnij datę gotowości do wyjazdu aby zobaczyć oferty.',
                'alert_type' => 'danger'
            ];
        }

        $caretaker = CRMCaretaker::with(['data' => function ($query) {
            $query->select('crt_id_data_caretaker', 'crt_id_language');
        }])
            ->where('crt_id_caretaker', $user->user_profiles->crt_id_caretaker)
            ->has('data')
            ->orderBy('crt_id_caretaker')
            ->get();

        $caretaker = $caretaker->first();

        if ($caretaker) {
            // pobranie wszystkich kodów pocztowych landu
            $zip_code = GermanZipCode::whereIn('land_id', $lands)->pluck('zip_code');

            // pobranie ofert dla opiekunki
            $offers = CRMPlaner::with(['family', 'family.patient', 'family.patient.mobility', 'family.patient.waking_up', 'family.address'])
                ->has('family.patient')
                ->whereIn('pnr_id_status', [1, 11])
                ->whereHas('family.address', function ($query) use ($zip_code) {
                    $query->whereIn('adr_postcode', $zip_code->toArray());
                })
                ->where('pnr_start_date', '>=', $user->ready_to_departure_dates->departure_date)
                ->where('pnr_id_rate_language', $caretaker->data->crt_id_language)
                ->where('pnr_caretaker_rate', '>=', 1000)
                ->where('pnr_caretaker_rate', '<=', 2700)
                ->whereRaw('pnr_caretaker_rate + 400 <= pnr_family_rate')
                ->orderBy('pnr_start_date', 'asc')
                ->get();

            $offers = $offers->toArray();
            $keys = array_keys($offers);
            shuffle($keys);

            $keys = array_slice($keys, 0, 3);

            $selected_offers = array_intersect_key($offers, array_flip($keys));
            unset($offers);

            return [
                'offers' => $selected_offers,
                'language_id' => $caretaker->data->crt_id_language
            ];
        } else {
            return [
                'success' => false,
                'alert_type' => 'danger',
                'msg' => 'Coś poszło nie tak. Spróbuj ponownie później.',
            ];
        }
    }
}
