<?php

namespace App\Repositories\Eloquents;

use App\Models\Role;
use App\Repositories\Contracts\RoleRepositoryInterface;

class RoleRepository implements RoleRepositoryInterface
{

       
public Role $model ;

    public function __construct(Role $role) {
        $this->model = $role;
    }


    public function getAllRoles()
    {
        return $this->model->all();
    }


    
}
