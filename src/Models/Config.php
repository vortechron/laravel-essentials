<?php

namespace Vortechron\Essentials\Models;

use Illuminate\Database\Eloquent\Model;

class Config extends Model
{
    protected $fillable = ['key', 'value'];

    public static function set($key, $value = null)
    {
        if (is_array($key)) {
            foreach ($key as $key => $value) {
                static::set($key, $value);
            }
            return;
        }

        $model = static::find($key, null, false);

        if (! $model) {
            $model = new static;
            $model->key = $key;
        }

        $model->value = $value;

        return $model->save();
    }

    public static function find($key, $default = null, $wantValue = true)
    {
        if ($value = static::where('key', $key)->first()) {
            return $wantValue ? $value->value : $value;
        }

        return $default;
    }
}
