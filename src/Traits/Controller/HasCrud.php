<?php

namespace Vortechron\Essentials\Traits\Controller;

use Illuminate\Support\Facades\View;
use Spatie\QueryBuilder\QueryBuilder;

trait HasCrud
{
    public function prepareIndexData($modelName, $title, $filters = [], $sorts = [], $paginate = 20, $callback = null)
    {
        $data = QueryBuilder::for($modelName)
            ->allowedFilters($filters)
            ->allowedSorts($sorts);

        if ($callback) $data = $callback($data);
        
        $data = $data->paginate($paginate);

        View::share('_title', $title);
        View::share('_data', $data);
    }

    public function prepareData($model, $title = '', $action = '', $deleteAction = '', $backAction = '')
    {
        View::share('_model', $model);
        View::share('_title', $title);
        View::share('_state', ($state = isset($model['id']) ? 'edit' : 'create'));
        View::share('_action', $action);
        View::share('_deleteAction', $deleteAction);
        View::share('_backAction', $backAction);

        View::share('_data', compact('model', 'title', 'state', 'action', 'deleteAction', 'backAction'));
    }
}