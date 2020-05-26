<?php

namespace Vortechron\Essentials\Http\Controllers;

use App\Http\Controllers\Controller;

class PermissionController extends Controller
{
    function check($name) {
        if (! user()->hasPermissionTo($name)) abort(403);

        return response('', 204);
     }
}

