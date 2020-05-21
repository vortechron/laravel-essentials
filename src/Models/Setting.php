<?php

namespace Vortechron\Essentials\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia\HasMedia;
use Vortechron\Essentials\Traits\HasMedia as TraitsHasMedia;

class Setting extends Model implements HasMedia
{
    use TraitsHasMedia;

    public $guarded = [];
}
