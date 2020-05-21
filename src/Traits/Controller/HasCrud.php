<?php

namespace Vortechron\Essentials\Traits\Controller;

use Illuminate\Support\Facades\View;
use Spatie\QueryBuilder\QueryBuilder;

trait HasCrud
{
    public function prepareIndexData($modelName, $title = '', $filters = [], $sorts = [], $paginate = 20, $callback = null, $namespace = '')
    {
        $data = QueryBuilder::for($modelName)
            ->allowedFilters($filters)
            ->allowedSorts($sorts);

        if ($callback) $data = $callback($data);
        
        $data = $data->paginate($paginate);

        View::share($namespace .'_title', $title);
        View::share($namespace .'_data', $data);
    }

    public function prepareData($model, $title = '', $action = '', $deleteAction = '', $backAction = '', $namespace = '')
    {
        View::share($namespace .'_model', $model);
        View::share($namespace .'_title', $title);
        View::share($namespace .'_state', ($state = isset($model['id']) ? 'edit' : 'create'));
        View::share($namespace .'_action', $action);
        View::share($namespace .'_deleteAction', $deleteAction);
        View::share($namespace .'_backAction', $backAction);

        View::share($namespace . '_data', compact('model', 'title', 'state', 'action', 'deleteAction', 'backAction'));
    }
}