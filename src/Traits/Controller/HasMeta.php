<?php

namespace Vortechron\Essentials\Traits\Controller;

trait HasMeta
{
    public function meta($title = null, $desc = null, $image = null)
    {
        $title = $title ?: model('setting')::getValue('general.name') ?? config('app.name', 'Laravel');
        $image = $image ?: asset('images/baituljannah-hero.jpg');
        $desc = $desc ?: 'Create your eCard for free with Welcm.to';

        MetaTag::set('title', $title);
        MetaTag::set('description', $desc);
        MetaTag::set('image', $image);
        MetaTag::set('twitter:image', $image);
    }
}