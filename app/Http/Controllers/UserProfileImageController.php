<?php

namespace App\Http\Controllers;

use App\Models\UserProfileImage;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;
use App\Helpers\Response;

use App\Services\Image\UserProfileImageService;
use Illuminate\Support\Facades\Storage;
// QUERY
// czyszczenie weryfikacji zdjęc
// update `user_profile_images` set status = 1, accepted_by_user_id = null, updated_at = null

class UserProfileImageController extends Controller
{
    private UserProfileImageService $_user_profile_image_service;

    public function __construct(UserProfileImageService $user_profile_image_service)
    {
        $this->_user_profile_image_service = $user_profile_image_service;
    }


    public function storeOrUpdate(Request $request)
    {
        return $this->_user_profile_image_service->store_or_update($request);
    }

    public function accept(Request $request)
    {
        return $this->_user_profile_image_service->accept($request);
    }

    public function massAccept(Request $request)
    {
        return $this->_user_profile_image_service->mass_accept($request);
    }

    public function decline(Request $request)
    {
        $profile_image = UserProfileImage::where('user_id', '=', $request->id)->first();
        $profile_image->status = $request->status;
        $profile_image->decline_info = $request->decline_info;
        $profile_image->accepted_by_user_id = Auth::user()->id;
        $profile_image->save();

        return response()->json($profile_image);
    }

    public function decline_2(Request $request)
    {
        try {
            DB::beginTransaction();

            $images = UserProfileImage::whereIn('user_id', $request->ids)->get();

            foreach ($images as $image) {
                $image->status = 2;
                Storage::disk('public_images')->delete($image->path);
                $image->save();
            }

            DB::commit();
        } catch (Exception $e) {
            DB::rollback();

            return Response::danger('Coś poszło nie tak podczas usuwania zdjęć. Niektóre z nich mogły zostać usunięte bez aktualizacji w bazie. Spróbuj ponownie później.', e: $e);
        }

        return Response::success('Wybrane zdjęcia zostały usunięte');
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
