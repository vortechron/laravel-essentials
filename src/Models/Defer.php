<?php

namespace Vortechron\Essentials\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\HasMedia\HasMediaTrait;

class Defer extends Model implements HasMedia
{
    use InteractsWithMedia;

    public $guarded = [];
}
