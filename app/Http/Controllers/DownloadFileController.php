<?php

namespace App\Http\Controllers;

use Inertia\Inertia;

class DownloadFileController extends Controller
{
    /**
     * Handle the incoming request.
     */
    public function files_to_download()
    {
        return Inertia::render('User/Files/Download');
    }

    public function pit()
    {
    }
}
