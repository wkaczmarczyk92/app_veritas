<?php

namespace App\Helpers;

use App\Services\CRM\DocumentService;

class CaretakerAgreementGetter
{
    public $caretaker;
    public $msg = null;

    public function get($crt_id_caretaker)
    {
        $document_service = new DocumentService();
        $this->caretaker = $document_service->short($crt_id_caretaker);

        $this->business()
            ->assignments()
            ->contract()
            ->registration()
            ->company();

        if ($this->msg != null) {
            return $this->msg;
        }

        return $this->caretaker->assignments[0]->contract->registration->company->cmp_company_name ?? 'nie znaleziono nazwy firmy';
    }

    public function back()
    {
        if ($this->msg != null) {
            return true;
        }

        return false;
    }

    public function business()
    {
        if ($this->back()) {
            return $this;
        }

        if (
            $this->caretaker->businness != null
            and $this->caretaker->business->bsn_id_business_type != null
            and in_array($this->caretaker->business->bsn_id_business_type, [2, 3])
        ) {
            $this->msg = 'własna działalność';
        }

        return $this;
    }

    public function assignments()
    {
        if ($this->back()) {
            return $this;
        }

        if (
            $this->caretaker->assignments == null
            || $this->caretaker->assignments[0] == null
        ) {
            $this->msg = 'nie znaleziono wyjazdu';
        }

        return $this;
    }

    public function contract()
    {
        if ($this->back()) {
            return $this;
        }

        if ($this->caretaker->assignments[0]->contract == null) {
            $this->msg = 'nie znaleziono umowy';
        }

        return $this;
    }

    public function registration()
    {
        if ($this->back()) {
            return $this;
        }

        if ($this->caretaker->assignments[0]->contract->registration == null) {
            $this->msg = 'nie znaleziono rekordu rejestracji';
        }

        return $this;
    }

    public function company()
    {
        if ($this->back()) {
            return $this;
        }

        if ($this->caretaker->assignments[0]->contract->registration->company == null) {
            $this->msg = 'nie znaleziono rekordu spółki';
        }

        return $this;
    }
}
