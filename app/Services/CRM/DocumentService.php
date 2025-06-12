<?php

namespace App\Services\CRM;

use App\Models\CRM\Caretaker;

class DocumentService
{
    public function full($crt_id_caretaker)
    {
        // pobieranie informacji o umowach, rejestracji, spółce opiekunki
        // najnowszy wyjazd
        // który nie jest anulowany
        // którego kontrakt jest umową lub aneksem
        return $caretaker = Caretaker::with([
            'business',
            'assignments' => function ($query) {
                $query->whereHas('contract', function ($query) {
                    $query->whereIn('con_id_contract_type', [1, 3, 4])
                        ->whereNull('con_when_canceled')
                        ->latest('con_creation_date');
                })
                    ->where('ssg_canceled', 0)
                    ->where('ssg_temporary_canceled', 0)
                    ->latest('ssg_arrival_date')
                    ->limit(1);
            },
            'assignments.contract' => function ($query) {
                $query->whereNull('con_when_canceled');
            },
            'assignments.contract.enclosure',
            'assignments.contract.registration',
            'assignments.contract.registration.company',
            'assignments.contract.contract_to',
            'assignments.contract.dpd',
            'assignments.contract.a1',
            'assignments.contract.a1.ekuz',
        ])
            ->where('crt_id_caretaker', $crt_id_caretaker)
            ->first();
    }

    public function short($crt_id_caretaker)
    {
        // skrócona wersja metody full
        return $caretaker = Caretaker::with([
            'business',
            'assignments.contract.registration',
            'assignments.contract.registration.company',
            'assignments' => function ($query) {
                $query->whereHas('contract', function ($query) {
                    $query->whereIn('con_id_contract_type', [1, 3, 4])
                        ->latest('con_creation_date');
                })
                    ->where('ssg_canceled', 0)
                    ->where('ssg_temporary_canceled', 0)
                    ->limit(1)->latest('ssg_arrival_date');
            }
        ])
            ->where('crt_id_caretaker', $crt_id_caretaker)
            ->first();
    }
}
