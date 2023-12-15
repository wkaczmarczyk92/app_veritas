<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LastLogin;

use Inertia\Inertia;
use Illuminate\Support\Facades\DB;

class LastLoginController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $last_logins = LastLogin::groupBy('date')
        // ->orderBy('date', 'desc')
        // ->get([
        //     DB::raw('DATE(created_at) as date'),
        //     DB::raw('COUNT(*) as login_count')
        // ])
        // ->paginate(1);
        // $last_logins = LastLogin::groupBy('date')
        // ->orderBy('date', 'desc')
        // ->get([
        //     DB::raw('DATE(created_at) as date'),
        //     DB::raw('COUNT(*) as login_count')
        // ])
        // ->paginate(1);

        $last_logins = LastLogin::with(['user'])
            ->whereHas('user', function($query) {
                $query->whereHas('roles', function($query) {
                    $query->where('id', 2);
                });
            })
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as login_count'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->paginate(50);

        // dd($last_logins);

        return Inertia::render('Admin/LastLogin', [
            'last_logins' => $last_logins
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
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
