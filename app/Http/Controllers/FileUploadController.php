<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class FileUploadController extends Controller
{
    public function uploadAndStoreFile(Request $request) {

        $validator = Validator::make($request->all(), [
            'file' => 'required|mimes:jpg,png'
        ]);

        if ($validator->fails()) {
            return response()
                ->json(['error' => $validator->errors()])
                ->setStatusCode(400);
        }

        if ($request->hasFile('file')) {
            if ($request->file('file')->isValid()) {
                $file = $request->file('file')->store('public');

                $url = asset(Storage::url($file));

                return response()
                    ->json(['url' => $url]);
            } else {
                return response()
                    ->json(['error' => 'File upload failed'])
                    ->setStatusCode(400);
            }

        } else {
            return response()
                ->json(['error' => 'No file found'])
                ->setStatusCode(400);
        }
    }
}
