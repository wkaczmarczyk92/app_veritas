<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\CaretakerRecommendation;
use App\Models\Language;
use Illuminate\Support\Facades\Auth;

use Inertia\Inertia;

use Illuminate\Support\Facades\DB;

use Illuminate\Validation\ValidationException;
use Exception;
use App\Helpers\CURLRequest;

use App\Http\Requests\Caretaker\UpdateRequest;


use App\Services\Caretaker\CaretakerRecommendationService;

class CaretakerRecommendationController extends Controller
{
    protected CaretakerRecommendationService $recommendation_service;

    public function __construct(CaretakerRecommendationService $recommendation_service)
    {
        $this->recommendation_service = $recommendation_service;
    }

    public function index(Request $request)
    {
        return Inertia::render('Admin/CaretakerRecommendations', [
            'data'      => $this->recommendation_service->ov_get($request->search ?? null),
            'search'    => $request->search ?? null
        ]);
    }

    public function store()
    {
        return response()->json($this->recommendation_service->store(Auth::user()->id));
    }

    public function show(string $id)
    {
        return Inertia::render('Admin/CaretakerRecommendation', [
            'data'      => $this->recommendation_service->find($id, ['user.user_profiles', 'admin_user.user_profiles'], \App\Models\CaretakerRecommendation::class),
            'language'  => Language::all()
        ]);
    }

    public function update(UpdateRequest $request)
    {
        return response()->json($this->recommendation_service->update($request->validated(), $request->id));
    }

    public function updateBonusPayout(Request $request)
    {
        $return_data = [];

        DB::beginTransaction();
        try {

            foreach ($request->items as $item) {
                $new_item = CaretakerRecommendation::with(['user.user_profiles', 'admin_user.user_profiles'])->find($item['id']);
                $new_item->bonus_payout_completed = true;
                $new_item->save();

                $return_data[] = $new_item;
            }

            DB::commit();
        } catch (\Throwable $th) {
            DB::rollback();
            dd($th->getMessage());
            return response()->json(['success' => false, 'msg' => 'Błąd podczas połączenia. Spróbuj ponownie później.']);
        }

        return response()->json(['success' => true, 'data' => $return_data]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
