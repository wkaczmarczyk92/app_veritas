<?php

namespace App\Services\Image;

use Illuminate\Http\Request;
use App\Helpers\Response;

use App\Models\UserProfileImage;
use Illuminate\Support\Facades\DB;
use App\Models\User;

use Exception;

class UserProfileImageService
{
    public function accept(Request $request)
    {
        $profile_image = UserProfileImage::where('user_id', '=', $request->id)->first();
        $profile_image->status = $request->status;
        $profile_image->accepted_by_user_id = auth()->id();
        $profile_image->save();

        return response()->json($profile_image);
    }

    public function mass_accept(Request $request)
    {
        DB::beginTransaction();

        try {
            foreach ($request->ids as $id) {
                $profile_image = UserProfileImage::where('user_id', '=', $id)->first();
                $profile_image->status = 3;
                $profile_image->accepted_by_user_id = auth()->id();
                $profile_image->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return Response::danger('Wystąpił błąd podczas połączenia. Spróbuj ponownie później.', e: $e->getMessage());
        }

        return Response::success('Wybrane zdjęcia zostały zaakceptowane.');
    }

    public function store_or_update(Request $request)
    {
        $admin_id = $request->accepted_by_user_id === true ? auth()->id() : null;
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
            return Response::danger('', e: $e);
        }

        $user->load('user_profile_image');

        return Response::success('', [
            'user_profile_image' => $user->user_profile_image
        ]);
    }
}
