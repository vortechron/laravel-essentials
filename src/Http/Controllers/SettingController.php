<?php

namespace Vortechron\Essentials\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Vortechron\Essentials\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        return view(config('laravel-essentials.admin.view_path').'.settings.index');
    }

    public function edit($group)
    {
        $form = view(config('laravel-essentials.admin.view_path').'.settings.'. $group);

        $settings = Setting::whereGroup($group)->get();
        $model = [];

        foreach ($settings as $setting) {
            if ($setting->value == '_is-media_') {
                $model[$setting->key] = $setting->getMedia();
            } else {
                $model[$setting->key] = $setting->value;
            }
        }

        $this->prepareData($model, ucfirst($group) . ' Settings', route(config('laravel-essentials.admin.route_prefix').'.settings.update', $group), '', route(config('laravel-essentials.admin.route_prefix').'.settings.index'));

        return view(config('laravel-essentials.admin.view_path').'.settings.template', compact('form'));
    }

    public function update(Request $request, $group)
    {
        foreach ($request->except('_token', '_redirect') as $key => $value) {
            $setting = Setting::updateOrCreate(['key' => $key, 'group' => $group], ['value' => is_array($value) ? json_encode($value) : $value]);

            if (is_array($value) && isset($value['media'])) {
                $setting->saveMedia($value);
                $setting->value = '_is-media_';
                $setting->save();
            } elseif (is_array($value) && isset($value['key'])) {
                $setting->delete();
            }
        }

        return $this->handleRedirect(route(config('laravel-essentials.admin.route_prefix').'.settings.edit', $group), route(config('laravel-essentials.admin.route_prefix').'.settings.index'));
    }
}
