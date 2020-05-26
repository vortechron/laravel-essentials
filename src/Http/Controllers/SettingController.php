<?php

namespace Vortechron\Essentials\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Vortechron\Essentials\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        return view('admin.setting.index');
    }

    public function edit($group)
    {
        $form = view('admin.setting.'. $group);

        $settings = Setting::whereGroup($group)->get();
        $model = [];

        foreach ($settings as $setting) {
            if ($setting->value == '_is-media_') {
                $model[$setting->key] = $setting->getMedia();
            } else {
                $model[$setting->key] = $setting->value;
            }
        }

        $this->prepareData($model, ucfirst($group) . ' Settings', route('admin.settings.update', $group), '', route('admin.settings.index'));

        return view('admin.setting.template', compact('form'));
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

        return $this->handleRedirect(route('admin.settings.edit', $group), route('admin.settings.index'));
    }
}
