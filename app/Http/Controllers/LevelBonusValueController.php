<?php

namespace App\Http\Controllers;

use App\Models\LevelBonusValue;
use Illuminate\Http\Request;

use Inertia\Inertia;
use App\Models\Level;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use Exception;



class LevelBonusValueController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd(Level::with('bonus_value')->get());
        return Inertia::render('Admin/LevelBonusValue', [
            'levels' => Level::with('bonus_value')->get()
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // dd($request);
        try {
            $validated = $request->validate([
                '*.value' => 'required|numeric'
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'success' => false,
                'errors' => $e->errors()
            ]);
        }

        DB::beginTransaction();

        try {

            for ($i = 0; $i < 4; $i++) {
                DB::table('level_bonus_values')->where('id', $request[$i]['id'])
                    ->update([
                        'value' => $request[$i]['value']
                    ]);
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'msg' => 'Wystąpił błąd podczas połączenia. Spróbuj ponownie później.',
                'alert_type' => 'danger',
                'exception' => $e->getMessage()
            ]);
        }

        return response()->json([
            'success' => true,
            'alert_type' => 'success',
            'msg' => 'Bonusy został zaktualizowane.',
            'levels' => Level::with('bonus_value')->get()
        ]);

    }

    /**
     * Display the specified resource.
     */
    public function show(LevelBonusValue $levelBonusValue)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(LevelBonusValue $levelBonusValue)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, LevelBonusValue $levelBonusValue)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(LevelBonusValue $levelBonusValue)
    {
        //
    }
}
