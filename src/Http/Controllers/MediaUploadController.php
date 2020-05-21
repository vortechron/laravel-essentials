<?php

namespace Vortechron\Essentials\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Vortechron\Essentials\Models\Defer;

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

    public function uploadManagerIndex(Request $request)
    {
        return [
            'media' => user()->getMedia('manager'),
            'igMedia' => user()->getMedia('ig-manager'),
        ];
    }
    public function uploadManager(Request $request)
    {
        $this->validate($request, [
            'media' => 'required|array',
            'media.*' => 'required|file',
        ]);

        foreach ($request->media as $media) {
            user()->addMedia($media)->toMediaCollection('manager');
        }

        return [
            'media' => user()->getMedia('manager')
        ];
    }

}

