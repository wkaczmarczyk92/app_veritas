<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Level;
use Illuminate\Support\Facades\Validator;

use App\Models\PointCheckpoint;

class PointCheckpointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd(Level::with('checkpoints')->get());
        return Inertia::render('Admin/PointCheckpoints', [
            'levels' => Level::with('checkpoints')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validator = Validator::make($request->levels, [
            '*.checkpoints.checkpoint' => 'required|integer|min:0'
        ]);

        if ($validator->fails()) {
            return back()->withErrors($validator->errors())->withInput();
        }

        foreach ($request->levels as $item) {
            $checkpoint = PointCheckpoint::find($item['checkpoints']['id']);
            $checkpoint->checkpoint = $item['checkpoints']['checkpoint'];

            $checkpoint->save();
        }

        return back()->with('success_msg', 'Dane zostały zaktualizowane.');
    }
}
