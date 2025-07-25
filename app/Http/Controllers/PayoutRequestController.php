<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\PayoutRequest;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use Exception;
use App\Models\Level;
use App\Models\BonusStatus;
use App\Helpers\Response;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\PayoutRequest\PayoutRequestStoreService;
use App\Services\PayoutRequest\PayoutRequestUpdateService;

class PayoutRequestController extends Controller
{
    public function index(Request $request)
    {
        return Inertia::render('Admin/PayoutRequests', [
            'levels' => Level::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        return (new PayoutRequestStoreService)($request);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        return (new PayoutRequestUpdateService)($request);
    }

    public function get(string $bonus_status)
    {
        return response()->json([
            PayoutRequest::with([
                'user_has_bonus.user.user_profiles',
                'admin_user',
                'admin_user.user_profiles',
                ])
                ->whereHas('user_has_bonus', function ($query) use ($bonus_status) {
                    $query->where('bonus_status_id', BonusStatus::where('name', $bonus_status)->value('id'));
                })
                ->orderBy('updated_at', 'desc')
                ->get()
        ]);
    }

    public function change_payout_status(Request $request)
    {
        try {
            $bonus_status = BonusStatus::where('name', $request->status)->firstOrFail();

            DB::transaction(function () use ($request, $bonus_status) {
                $payout_requests = PayoutRequest::with('user_has_bonus')->whereIn('id', $request->ids)->get();
                
                if (!$payout_requests) {
                    throw new \Throwable('Brak wybranych wniosków.');
                }

                foreach ($payout_requests as $payout_request) {
                    $payout_request->user_has_bonus->bonus_status_id = $bonus_status->id;
                    $payout_request->updated_at = now();

                    if ($request->status == 'completed') {
                        $payout_request->user_id_completed_by = Auth::user()->id;
                    }

                    $payout_request->save();
                    $payout_request->user_has_bonus->save();
                }

            });
        } catch (ModelNotFoundException $e) {
            return Response::danger('Nie znaleziono modelu.', e: $e);
        } catch (\Throwable $th) {
            return Response::danger($th->getMessage(), e: $th);
        }

        return Response::success('Wnioski zostały zaktualizowane.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
