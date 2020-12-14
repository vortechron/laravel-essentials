<?php

namespace Vortechron\Essentials\Http\Controllers;

use GuzzleHttp\Client;
use App\InstagramMedia;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Http;

class InstagramController extends Controller
{
    public function auth()
    {
        $appId = config('services.instagram.client_id');
        $redirectUri = urlencode(config('services.instagram.redirect'));

        return redirect()->to("https://api.instagram.com/oauth/authorize?app_id={$appId}&redirect_uri={$redirectUri}&scope=user_profile,user_media&response_type=code");
    }

    public function redirect(Request $request)
    {
        $code = $request->code;
        if (empty($code)) error('Error!');

        $appId = config('services.instagram.client_id');
        $secret = config('services.instagram.client_secret');
        $redirectUri = config('services.instagram.redirect');

        $client = new Client();

        // Get access token
        $response = $client->request('POST', 'https://api.instagram.com/oauth/access_token', [
            'form_params' => [
                'app_id' => $appId,
                'app_secret' => $secret,
                'grant_type' => 'authorization_code',
                'redirect_uri' => $redirectUri,
                'code' => $code,
            ]
        ]);

        if ($response->getStatusCode() != 200) {
            return redirect()->route('home')->with('error', 'Unauthorized login to Instagram.');
        }

        $content = $response->getBody()->getContents();
        $content = json_decode($content);

        $accessToken = $content->access_token;
        $userId = $content->user_id;

        return redirect()->route('instagram.import', ['access_token' => $accessToken]);
    }

    public function import(Request $request)
    {
        $client = new Client();
        $accessToken = $request->access_token;

        // Get user info
        $response = $client->request('GET', "https://graph.instagram.com/me/media?fields=id,caption&access_token={$accessToken}");

        $content = $response->getBody()->getContents();
        $oAuth = json_decode($content);

        user()->getMedia('ig-manager')->each(function ($model) {
            $model->delete();
        });
        
        foreach ((array) $oAuth->data as $rawMedia) {
            $response = Http::get("https://graph.instagram.com/{$rawMedia->id}?fields=id,media_type,media_url,username,timestamp,caption&access_token={$accessToken}");
            $content = $response->json();

            $media = user()->addMediaFromUrl($content['media_url'])->toMediaCollection('ig-manager');
            
            // get tags from captions
            preg_match_all('/#[^\s#]*/i', $content['caption'], $matches);

            $media->setCustomProperty('description', $content['caption'])
                ->setCustomProperty('tags', isset($matches[0]) ? implode('|', $matches[0]) : '')
                ->setCustomProperty('type', 'instagram');

            $media->created_at = carbon($content['timestamp'])->format(timestamp_format());
            $media->save();
        }

        return redirect()->route('closeable');
    }
}
