<?php

namespace App\Services;

use App\Models\User;
use App\Models\LastLogin;

use Illuminate\Support\Facades\DB;

class UserService
{
    public function latest(int $take = 10, bool $only_users = true)
    {
        $users = User::with(['user_profiles', 'roles']);

        if ($only_users) {
            $users = $users->whereHas('roles', function ($query) {
                $query->where('id', 2);
            });
        }

        return $users->latest()
            ->take($take)
            ->get();
    }

    // generuje liste ostatnich logowan (ostatnie 5 dni)
    // jesli $admin = true, to bierze adminów pod uwagę
    // podstawia 0 jesli nie ma logowan w danym dniu
    public function last_logins($admin = false)
    {
        $last_logins = LastLogin::with(['user'])
            ->whereHas('user', function ($query) {
                $query->whereHas('roles', function ($query) {
                    $query->where('id', 2);
                });
            })
            ->select(DB::raw('DATE(created_at) as date'), DB::raw('COUNT(*) as login_count'))
            ->groupBy('date')
            ->orderBy('date', 'desc')
            ->take(5)
            ->get();

        return $last_logins;
        // $dates = collect([
        //     date('Y-m-d'),
        //     date('Y-m-d', strtotime('-1 days')),
        //     date('Y-m-d', strtotime('-2 days')),
        //     date('Y-m-d', strtotime('-3 days')),
        //     date('Y-m-d', strtotime('-4 days')),
        // ]);

        // $users_last_logins = LastLogin::with(['user']);

        // if (!$admin) {
        //     $users_last_logins = $users_last_logins->whereHas('user', function($query) {
        //         $query->whereHas('roles', function($query) {
        //             $query->where('id', 2);
        //         });
        //     });
        // }

        // $users_last_logins = $users_last_logins->select(DB::raw('DATE(created_at) as date'), DB::raw('count(*) as total'))
        //     ->whereIn(DB::raw('DATE(created_at)'), $dates)
        //     ->groupBy('date')
        //     ->latest()
        //     ->take(5)
        //     ->get();

        // $max = $users_last_logins->max('total');

        // $result = $dates->mapWithKeys(function($date) use ($users_last_logins, $max) {
        //     $date = date('Y-m-d', strtotime($date));
        //     $total = $users_last_logins->where('date', $date)->first();
        //     return [
        //         $date => [
        //             'count'     => $total ? $total->total : 0,
        //             'max'       => $total && $max == $total->total ? true : false
        //         ]];
        //     });

        // return $result;
    }
}
