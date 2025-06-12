<?php

namespace App\Http\Controllers;

use App\Services\CRM\ProfileImageService;

class DownloadCRMProfileImage extends Controller
{
    private ProfileImageService $_profile_image_service;

    public function __construct(ProfileImageService $profile_image_service)
    {
        $this->_profile_image_service = $profile_image_service;
    }

    public function download(int $id)
    {
        return $this->_profile_image_service->download($id);
    }
}
