<?php

namespace Vortechron\Essentials\Traits\Controller;

use Illuminate\Support\Facades\View;

trait HasCrud
{
    public function prepareData($model, $actionRoute)
    {
        View::share('_model', $model);
        View::share('_state', is_null($model['id']) ? 'create' : 'edit');
        View::share('_action', $actionRoute);
    }
}