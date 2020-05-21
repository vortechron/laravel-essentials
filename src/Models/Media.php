<?php

namespace Vortechron\Essentials\Models;

class Media extends \Spatie\MediaLibrary\Models\Media
{
    use \Vortechron\Essentials\Traits\Modeler;

    protected $table = 'media';

    protected $appends = ['full_url'];

    public function getFullUrlAttribute()
    {
        return asset($this->getUrl());
    }
}
