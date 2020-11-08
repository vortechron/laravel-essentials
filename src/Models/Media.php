<?php

namespace Vortechron\Essentials\Models;

use Spatie\MediaLibrary\MediaCollections\Models\Media as ModelsMedia;

class Media extends ModelsMedia
{
    use \Vortechron\Essentials\Traits\Modeler;

    protected $table = 'media';

    protected $appends = ['full_url'];

    public function getFullUrlAttribute()
    {
        return asset($this->getUrl());
    }
}