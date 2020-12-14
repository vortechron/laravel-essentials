<?php

namespace Vortechron\Essentials\Http\Controllers;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Vortechron\Essentials\Models\Defer;

class MediaUploadController extends Controller
{
    public function upload(Request $request)
    {
        $this->validate($request, [
            'media' => 'required|array',
            'media.*' => 'required|file',
        ]);

        $defer = Defer::create(['session_key' => Str::random(7)]);
        foreach ($request->media as $media) {
            $defer->addMedia($media)->toMediaCollection();
        }

        $medias = $defer->getMedia()->map(function ($media) {
            return array_merge([
                'full_url' => $media->getFullUrl(),
            ], $media->toArray());
        });

        return [
            'media' => $medias->toArray()
        ];
    }

    public function uploadManagerIndex(Request $request)
    {
        return [
            'media' => user()->getMedia('manager')->toArray(),
            'igMedia' => user()->getMedia('ig-manager')->toArray(),
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

