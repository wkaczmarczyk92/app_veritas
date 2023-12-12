<?php

namespace App\Http\Controllers;

use App\Models\UserProfileImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;



// QUERY
// czyszczenie weryfikacji zdjęc
// update `user_profile_images` set status = 1, accepted_by_user_id = null, updated_at = null

class UserProfileImageController extends Controller
{
    public function storeOrUpdate(Request $request)
    {  
        $admin_id = $request->accepted_by_user_id === true ? Auth::user()->id : null;
        $user = User::with('user_profile_image')->find($request->id);

        DB::beginTransaction();

        try {
            $filename = "{$request->id}.{$request->file('file')->extension()}";
            $path = $request->file('file')->storeAs('', $filename, 'public_images');
            $user->user_profile_image()->updateOrCreate([
                'user_id' => $request->id
            ], [
                'path' => $filename,
                'status' => $request->status,
                'accepted_by_user_id' => $admin_id
            ]);

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'msg' => $e->getMessage()
            ]);
        }

        $user->load('user_profile_image');

        return response()->json([
            'success' => true,
            'user_profile_image' => $user->user_profile_image
        ]);
    }

    public function accept(Request $request) {
        $profile_image = UserProfileImage::where('user_id', '=', $request->id)->first();
        $profile_image->status = $request->status;
        $profile_image->accepted_by_user_id = Auth::user()->id;
        $profile_image->save();

        return response()->json($profile_image);
    }

    public function massAccept(Request $request) {
        DB::beginTransaction();
        
        try {
            foreach ($request->ids as $id) {
                $profile_image = UserProfileImage::where('user_id', '=', $id)->first();
                $profile_image->status = 3;
                $profile_image->accepted_by_user_id = Auth::user()->id;
                $profile_image->save();
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
            'msg' => 'Wybrane zdjęcia zostały zaakceptowane.'
        ]);
    }

    public function decline(Request $request) {
        $profile_image = UserProfileImage::where('user_id', '=', $request->id)->first();
        $profile_image->status = $request->status;
        $profile_image->decline_info = $request->decline_info;
        $profile_image->accepted_by_user_id = Auth::user()->id;
        $profile_image->save();

        return response()->json($profile_image);
    }

    public function verify()
    {
        return Inertia::render('Admin/VerifyProfileImages');
    }


    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        return response()->json([
            'users' => User::with(['user_profiles', 'user_profile_image'])
                        ->whereHas('user_profile_image', function ($query) {
                            $query->where('status', '=', 1);
                        })->get()
        ]);
    }
}
