<?php

namespace Vortechron\Essentials\Models;

use Vortechron\Essentials\Traits\Modeler;
use Spatie\Permission\Models\Role as ModelsRole;

class Role extends ModelsRole
{
    use Modeler;

    protected $fillable = ['name', 'guard_name', 'created_at', 'updated_at'];
}