<?php 

namespace Vortechron\Essentials\Traits;

use IlluminateAgnostic\Str\Support\Str;

trait Modeler
{
    public function forModel($with)
    {
        $this->load($with);

        if (! empty($this->getAttributes())) {
            $attributes = $this->toArray();
        } else {
            $attributes = $this->getAllAttributes($with);
        }

        // Normalize Null Relation To Empty Model
        foreach ($attributes as $key => $value) {
            if (
                is_null($value) && 
                method_exists($this, Str::camel($key)) && 
                $this->{Str::camel($key)}() instanceOf \Illuminate\Database\Eloquent\Relations\Relation
            ) {
                $relation = $this->{Str::camel($key)}();
                $model = $relation->getModel();
                $attributes[$key] = $model->getAllAttributes();
            }
        }

        return $attributes;
    }

    public function getAllAttributes($with = [])
    {
        $columns = array_merge($this->getArrayableAppends(), $this->getFillable(), $with);
        $attributes = $this->getAttributes();
        foreach ($columns as $column)
        {
            $column = Str::snake($column);
            if (! array_key_exists($column, $attributes)) $attributes[$column] = null;
        }

        return $attributes;
    }
}