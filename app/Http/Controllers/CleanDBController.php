<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CleanDBController extends Controller
{
    public function clean() {
        DB::table('bok_requests')->delete();
        DB::table('caretaker_recommendations')->delete();
        DB::table('contact_forms')->delete();
        DB::table('family_recommendations')->delete();
        DB::table('password_requests')->delete();
        DB::table('payout_requests')->delete();
        DB::table('user_has_bonuses')->delete();
        DB::table('user_points')->delete();
        DB::table('user_profile_images')->delete();
        DB::table('password_for_users')->delete();

        // DB::table('user_profiles')->update([
        //     'level' => 1,
        //     'total_points' => null,
        //     'current_points' => null,
        //     'total_days' => null
        // ]);

        DB::table('user_profiles')->delete();
        DB::table('users')->where('id', '!=', 1)->delete();

    }
}
