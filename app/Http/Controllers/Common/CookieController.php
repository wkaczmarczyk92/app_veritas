<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helpers\Cookie;

class CookieController extends Controller
{
    public function set_alert_cookie(Request $request)
    {
        $cookie = cookie('show_contract_and_a1_check_alert', true, 10080);
        return redirect()->back()->withCookie($cookie);
    }
}
