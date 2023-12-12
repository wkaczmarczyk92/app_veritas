<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Http\Request;

use App\Models\CRMLead;
use App\Models\CaretakerRecommendation;

class CRONCaretakerProfileExists extends Command
{
    // TOKEN = HbwAJytaY515nTG8fFjXjKNgbQZwIh

    protected $signature = 'my:cron-task';
  
    public function handle()
    {
        // pobrac polecenia gdzie jest CRM ID LEAD ale nie ma CRT ID CARETAKER
        // pobrac dla kazdego rekordu wartosc LDL_ID_CARETAKER z tabeli leads - BAZA CRM
        // jesli CRT ID CARETAKER jest rozne od nulla zaktualizuj rekomendacje opiekunki

        $caretaker_recommendations = CaretakerRecommendation::whereNotNull('crm_lead_id')
            ->whereNull('crt_id_caretaker')
            ->get();

        foreach ($caretaker_recommendations as $index => $obj) {
            $crm_lead = null;
            $crm_lead = CRMLead::find($obj->crm_lead_id);

            if ($crm_lead->ldl_id_caretaker != null) {
                $obj->crt_id_caretaker = $crm_lead->ldl_id_caretaker;
                $obj->save();
            }
        }        
    }
}