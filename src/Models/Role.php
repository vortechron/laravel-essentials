<?php

namespace Vortechron\Essentials\Models;

use Spatie\Permission\Models\Role as ModelsRole;
use Vortechron\Essentials\Traits\ModelEssentials;

class Role extends ModelsRole
{
    use ModelEssentials;

    protected $fillable = ['name', 'guard_name', 'created_at', 'updated_at'];
}