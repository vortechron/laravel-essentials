<?php

namespace Vortechron\Essentials\Traits\Controller;

use Illuminate\Support\Facades\View;

trait HasHelpers
{
    protected function handleRedirect($current, $orBack)
    {
        $redirect = request('_redirect') == "true" ? true : false;
        $withRedirect = $orBack instanceof \Illuminate\Http\RedirectResponse;

        if (! $redirect) return $withRedirect ? $orBack : redirect()->to($orBack);

        return redirect()->to($current);
    }

    protected function prepareCountriesData()
    {
        View::share('_countries', \Storage::get('countries'));
    }

    protected function inertia($component = null, $props = [])
    {
        foreach (View::getShared() as $key => $value) {
            \Inertia\Inertia::share($key, $value);
        }

        return inertia($component, $props);
    }
}