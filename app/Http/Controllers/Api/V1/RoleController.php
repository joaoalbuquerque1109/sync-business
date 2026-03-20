<?php

namespace App\Http\Controllers\Api\V1;

use App\Models\Permission;
use App\Models\Role;

class RoleController extends Controller
{
    public function index()
    {
        return $this->success(Role::query()->with('permissions')->get());
    }

    public function permissions()
    {
        return $this->success(Permission::query()->get());
    }
}
