<?php

namespace App\Services\PayoutRequest;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Models\PayoutRequest;
use App\Models\BonusStatus;
use App\Helpers\Response;
use Exception;

class PayoutRequestUpdateService
{
    public function __invoke($request)
    {
        DB::beginTransaction();

        try {
            foreach ($request->to_update as $id) {
                $payout_request = PayoutRequest::with('user_has_bonus')->find($id);

                if ($request->status == 'in_progress') {
                    $payout_request->user_id_approved_by = Auth::user()->id;
                }

                if ($request->status == 'completed') {
                    $payout_request->user_id_completed_by = Auth::user()->id;
                }

                $payout_request->save();
                $status_id = BonusStatus::where('name', $request->status)->value('id');

                $payout_request->user_has_bonus->bonus_status_id = $status_id;
                $payout_request->user_has_bonus->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return Response::danger('Wystąpił błąd podczas aktualizacji wypłaty.', e: $e);
        }

        return Response::success('Wypłata została zaktualizowana pomyślnie.');
    }
}
