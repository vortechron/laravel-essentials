<?php

namespace Vortechron\Essentials\Core;

use Illuminate\Support\Facades\Route;

class Essentials
{
    public static function settingsRoute()
    {
        Route::get('/settings', '\Vortechron\Essentials\Http\Controllers\SettingController@index')->name('settings.index');
        Route::get('/settings/{group}', '\Vortechron\Essentials\Http\Controllers\SettingController@edit')->name('settings.edit');
        Route::post('/settings/{group}', '\Vortechron\Essentials\Http\Controllers\SettingController@update')->name('settings.update');
    }

    public static function notificationsRoute()
    {
        Route::resource('notifications', '\Vortechron\Essentials\Http\Controllers\NotificationController');

    }
}
