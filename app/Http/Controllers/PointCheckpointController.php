<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

use App\Models\Level;
use Illuminate\Support\Facades\Validator;

use App\Models\PointCheckpoint;
use Illuminate\Support\Facades\DB;
use App\Helpers\Response;
use Exception;
use App\Http\Requests\Settings\CheckpointUpdateRequest;

class PointCheckpointController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // dd(Level::with('checkpoints')->get());
        return Inertia::render('Admin/Settings/Checkpoints', [
            'levels' => Level::with('checkpoints')->get()
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(CheckpointUpdateRequest $request)
    {
        try {
            DB::beginTransaction();

            foreach ($request->levels as $item) {
                $checkpoint = PointCheckpoint::find($item['checkpoints']['id']);
                $checkpoint->checkpoint = $item['checkpoints']['checkpoint'];

                $checkpoint->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return Response::danger('Coś poszło nie tak. Spróbuj ponownie później.', e: $e);
        }

        return Response::success('Dane zostały zaktualizowane.');
    }
}
