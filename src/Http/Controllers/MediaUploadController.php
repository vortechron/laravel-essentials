<?php

namespace Vortechron\Essentials\Http\Controllers;

use App\Defer;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class MediaUploadController extends Controller
{
    public function upload(Request $request)
    {
        $this->validate($request, [
            'key' => 'required|string|min:1',
            'media' => 'required|array',
            'media.*' => 'required|file',
        ]);

        $defer = Defer::create(['session_key' => $request->key]);
        foreach ($request->media as $media) {
            $defer->addMedia($media)->toMediaCollection();
        }

        return [
            'media' => $defer->getMedia()
        ];
    }

}

