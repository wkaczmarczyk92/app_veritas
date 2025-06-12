<?php

namespace App\Services\CRM;

use App\Models\User;
use App\Models\UserProfile;
use App\Models\CRMProfileImage;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

use Exception;

use App\Helpers\Response;

use App\Helpers\CRMConfig;

class ProfileImageService
{
    public function download(int $id)
    {
        $crt_id_caretaker = UserProfile::where('user_id', $id)->value('crt_id_caretaker');
        // dd($crt_id_caretaker);

        if (!$crt_id_caretaker) {
            return Response::danger('Nie udało się pobrać ID opiekunki z systemu.');
        }

        $crm_profile_image_path = CRMProfileImage::on('crm_database')
            ->where('cph_id_caretaker', $crt_id_caretaker)
            ->where('cph_id_image_type', 3)
            ->pluck('cph_image_path');

        if (!isset($crm_profile_image_path[0])) {
            return Response::danger('Nie znaleziono zdjęcia w CRM.');
        }

        $crm_profile_image_path = $crm_profile_image_path[0];

        $extension = pathinfo($crm_profile_image_path, PATHINFO_EXTENSION);

        $path = CRMConfig::$base_url . $crm_profile_image_path;
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
                'accepted_by_user_id' => auth()->id()
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollback();
            return Response::danger('Nie udało się zapisać zdjęcia.', e: $e);
        }

        $user->load('user_profile_image');

        return Response::success('Zdjęcie zostało zapisane.', [
            'user_profile_image' => $user->user_profile_image
        ]);
    }
}
