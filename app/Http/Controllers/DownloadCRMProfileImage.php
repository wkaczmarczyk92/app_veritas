<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

use App\Models\User;
use Exception;

use Illuminate\Support\Facades\Auth;

use App\Models\CRMProfileImage;
use Illuminate\Support\Facades\DB;

use App\Models\UserProfile;

class DownloadCRMProfileImage extends Controller
{
    public function download(int $id) {
        $crt_id_caretaker = UserProfile::where('user_id', $id)->pluck('crt_id_caretaker');

        $crm_profile_image_path = CRMProfileImage::on('crm_database')
            ->where('cph_id_caretaker', '=', $crt_id_caretaker)
            ->where('cph_id_image_type', '=', 3)
            ->pluck('cph_image_path');

        // dd($crm_profile_image_path);

        if (!isset($crm_profile_image_path[0])) {
            return response()->json([
                'success' => false,
                'msg' => 'Nie znaleziono zdjęcia w CRM.'
            ]);
        }

        $crm_profile_image_path = $crm_profile_image_path[0];

        $extension = pathinfo($crm_profile_image_path, PATHINFO_EXTENSION);

        $path = 'https://local.grupa-veritas.pl' . $crm_profile_image_path;
        $response = Http::get($path);
        $image = $response->body();

        DB::beginTransaction();

        try {
            $filename = "{$id}.{$extension}";
            $path = Storage::disk('public_images')->put($filename, $image);
            
            $user = User::with('user_profile_image')->find($id);
            $user->user_profile_image()->updateOrCreate([
                'user_id' => $id
            ], [
                'path' => "{$id}.{$extension}",
                'status' => 3,
                'accepted_by_user_id' => Auth::user()->id
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
            'msg' => 'Zdjęcie zostało zapisane.',
            'user_profile_image' => $user->user_profile_image
        ]);

        dd($image);


        dd($id);
    }
}
