<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Multiplier;
use App\Models\Level;

use Illuminate\Support\Facades\Validator;
// use Inertia\Response;
// use Inertia\ResponseFactory;

class MultiplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('Admin/Multiplier', [
            'levels' => Level::with('multiplier')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->levels, [
            '*.multiplier.multiplier_value' => 'required|decimal:0,2|min:1'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        }

        foreach ($request->levels as $item) {
            $multiplier = Multiplier::find($item['multiplier']['id']);
            $multiplier->multiplier_value = $item['multiplier']['multiplier_value'];

            $multiplier->save();
        }

        return back()->with('success_msg', 'Dane zostały zaktualizowane.');
    }
}
