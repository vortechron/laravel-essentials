<?php 

namespace Vortechron\Essentials\Traits;

use IlluminateAgnostic\Str\Support\Str;

trait Modeler
{
    public function forModel($with)
    {
        $this->load($with);

        $attributes = $this->getAllAttributes($with);

        // Normalize Null Relation To Empty Model
        foreach ($attributes as $key => $value) {
            if (
                is_null($value) && 
                method_exists($this, Str::camel($key)) && 
                $this->{Str::camel($key)}() instanceOf \Illuminate\Database\Eloquent\Relations\Relation
            ) {
                $relation = $this->{Str::camel($key)}();
                $model = $relation->getModel();
                $attrRelation = $model->getAllAttributes();
                if ($relation instanceof \Illuminate\Database\Eloquent\Relations\HasOneOrMany) {
                    $attributes[$key] = [];
                } else {
                    $attributes[$key] = $attrRelation;
                }
            }
        }

        return $attributes;
    }

    public function getAllAttributes($with = [])
    {
        $forModelAttributes = isset($this->for_model) ? $this->for_model : [];

        $forModelAttributes = collect($forModelAttributes)->transform(function ($att) {
            return '_data_' . $att;
        })->toArray();

        $columns = array_merge($this->getArrayableAppends(), $this->getFillable(), $with, $forModelAttributes);

        $ifHas = $this->toArray();
        $attributes = !empty($ifHas) ? $this->toArray() : $this->getAttributes();

        foreach ($columns as $column)
        {
            $column = Str::snake($column);
            if (! array_key_exists($column, $attributes)) {
                if ($this->hasForModelMutator($column)) {
                    $attributes[$column] = $this->mutateForModelAttribute($column, null);
                } else if ($this->hasGetMutator($column)) {
                    $attributes[$column] = $this->mutateAttribute($column, null);
                } else {
                    $attributes[$column] = null;
                }
            };
        }

        return $attributes;
    }

        /**
     * Determine if a get mutator exists for an attribute.
     *
     * @param  string  $key
     * @return bool
     */
    public function hasForModelMutator($key)
    {
        if(strpos($key, '_data_') !== false){
            $key = str_replace('_data_', '', $key);
            return method_exists($this, 'get'.Str::studly($key).'ForModel');
        }

        return false;
    }

    /**
     * Get the value of an attribute using its mutator.
     *
     * @param  string  $key
     * @param  mixed  $value
     * @return mixed
     */
    protected function mutateForModelAttribute($key, $value)
    {
        $key = str_replace('_data_', '', $key);

        return $this->{'get'.Str::studly($key).'ForModel'}($value);
    }
}