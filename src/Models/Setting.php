<?php

namespace Vortechron\Essentials\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Vortechron\Essentials\Traits\HasMedia as TraitsHasMedia;

class Setting extends Model implements HasMedia
{
    use TraitsHasMedia;

    public $guarded = [];

    public static function find($group, $key, $default = null, $wantValue = true)
    {
        if ($value = static::where('group', $group)->where('key', $key)->first()) {
            return $wantValue ? $value->value : $value;
        }

        return $default;
    }
}
