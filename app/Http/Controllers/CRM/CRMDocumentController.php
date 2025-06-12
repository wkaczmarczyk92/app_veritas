<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use Inertia\Inertia;

use App\Models\CRM\Caretaker;
use App\Models\CRM\Assignment;
use App\Models\CRM\Contract;

use App\Models\CRM\Registration;

use App\Services\CRM\DocumentService;
use DateTimeImmutable;
// use App\Helpers\Cookie;

use Symfony\Component\HttpFoundation\Cookie;

use App\Helpers\Response;

class CRMDocumentController extends Controller
{
    private DocumentService $document_service;

    public function __construct(DocumentService $document_service)
    {
        $this->document_service = $document_service;
    }


    public function index()
    {
        $user = auth()->user();
        $user->load('user_profiles');

        $caretaker = $this->document_service->full($user->user_profiles->crt_id_caretaker);

        return Inertia::render('User/Documents/Index', [
            'caretaker' => $caretaker,
        ]);
    }

    public function contract_and_a1_check(Request $request)
    {
        if ($request->cookie('show_contract_and_a1_check_alert')) {
            return Response::danger('Ciasteczko istnieje', ['code' => 0]);
        }

        $user = auth()->user();
        $user->load('user_profiles');

        $caretaker = $this->document_service->full($user->user_profiles->crt_id_caretaker);
        $msg = null;

        if (
            $caretaker == null
            or $caretaker->assignments[0] == null
            or $caretaker->assignments[0]->contract == null
            or $caretaker->assignments[0]->contract->enclosure == null
            or $caretaker->assignments[0]->contract->con_send_date == null
        ) {
            return Response::danger('Nie znaleziono umowy lub załączników.', ['code' => 0]);
        }

        $current_date = new DateTimeImmutable();
        $submission_date = new DateTimeImmutable($caretaker->assignments[0]->contract->con_send_date);

        $days_diff = $submission_date->diff($current_date)->days;

        if (
            (
                $days_diff > 10
                and $caretaker->assignments[0]->contract->con_receive_date == null
            )
            or
            (
                $caretaker->assignments[0]->contract->con_receive_date != null
                and in_array($caretaker->assignments[0]->contract->enclosure->enc_signed, [null, false, 0])
            )
        ) {
            $msg = 'Przypominamy o przesłaniu wszystkich podpisanych dokumentów. Dziękujemy!';
        } else if ($days_diff > 10 and $caretaker->assignments[0]->contract->con_receive_date != null and in_array($caretaker->assignments[0]->contract->enclosure->enc_us_55, [null, false, 0])) {
            $msg = 'Przypominamy o przesłaniu podpisanego załącznika US55, bez niego nie możemy wystąpić z wnioskiem o A1. Dziękujemy!';
        }

        if ($msg == '') {
            return Response::danger('Nie znaleziono żadnych dokumentów.', ['code' => 0]);
        }

        return Response::success($msg, ['code' => 1]);
    }
}
