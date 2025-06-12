<?php

namespace App\Services\Export;

use App\Exports\PayoutRequestsExport;
use Maatwebsite\Excel\Facades\Excel;

use Maatwebsite\Excel\Concerns\FromCollection;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use App\Models\UserHasBonus;

use App\Models\BonusStatus;
use App\Services\CRM\DocumentService;

class PayoutRequestsExportService
{
    public $alert_rows = [];

    public function company_name($d, $i)
    {
        if (
            $d->businness != null
            and $d->business->bsn_id_business_type != null
            and in_array($d->business->bsn_id_business_type, [2, 3])
        ) {
            return 'własna działalność';
        }

        if (
            $d->assignments != null
            and $d->assignments[0] != null
            and $d->assignments[0]->contract != null
            and $d->assignments[0]->contract->registration != null
            and $d->assignments[0]->contract->registration->company != null
        ) {
            return $d->assignments[0]->contract->registration->company->cmp_company_name;
        }

        // $this->alert_rows[] = $i;
        return 'brak informacji';
    }

    public function collect_data()
    {
    }
}
