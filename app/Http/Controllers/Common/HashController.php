<?php

namespace App\Http\Controllers\Common;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

use App\Helpers\Response;
use App\Models\Common\File;

class HashController extends Controller
{
    public function get(Request $request)
    {
        if (!isset($request->hash) or empty($request->input('hash'))) {
            return Response::danger('Brak hashu.');
        }

        $file = File::with('mime_type')->where('hash', $request->input('hash'))->first();
        return Response::success('Tekst został skonwertowany.', [
            'file' => $file,
        ]);
    }
}
