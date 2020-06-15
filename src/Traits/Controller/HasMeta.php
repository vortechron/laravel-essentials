<?php

namespace Vortechron\Essentials\Traits\Controller;

use Torann\LaravelMetaTags\Facades\MetaTag;

trait HasMeta
{
    public function meta($title = null, $desc = null, $image = null)
    {
        if (func_num_args() == 0) return app('metatag');

        $title = $title ?: model('setting')::getValue('general.title') ?? config('meta-tags.title', 'Laravel');
        $desc = $desc ?: model('setting')::getValue('general.description') ?? config('meta-tags.description', 'Laravel');
        $image = asset($image ?: model('setting')::getValue('general.image') ?? config('meta-tags.image', 'favicon.png') );

        MetaTag::set('title', $title);
        MetaTag::set('description', $desc);
        MetaTag::set('image', $image);
        MetaTag::set('twitter:image', $image);
    }
}