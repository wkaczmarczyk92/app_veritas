<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\UserProfile;
use App\Models\ReadyToDepartureDate;


class APIReadyToDepartureController extends Controller
{
    // {
    //     "crt_id_caretaker": 31471,
    //     "date": "2023-07-12"
    // }

    public function update(Request $request)
    {
        $user_id = UserProfile::where('crt_id_caretaker', '=', $request->carerId)->pluck('user_id')[0] ?? null;
        if ($user_id == null) {
            echo json_encode([
                'success' => true,
                'msg' => 'Nie znaleziono użytkownika.'
            ]);
            return;
        }

        $rtp = ReadyToDepartureDate::where('user_id', '=', $user_id)->first();
        if ($request->date == null and $rtp != null) {
            $rtp->delete();
        } else if ($rtp != null) {
            $rtp->departure_date = $request->data['crt_availability_date'];
            $rtp->save();
        } else {
            ReadyToDepartureDate::create([
                'user_id' => $user_id,
                'departure_date' => $request->data['crt_availability_date']
            ]);
        }
        
        echo json_encode([
            'success' => true
        ], JSON_UNESCAPED_UNICODE);
        return;
    }
}