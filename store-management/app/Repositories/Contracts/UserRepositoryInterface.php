<?php

namespace App\Repositories\Contracts;

use App\Models\User;

interface UserRepositoryInterface
{

    public function create(array $data) ;

    public function attachRoles(User $user, array $roleIds);

    public function findById($id);

    public function findByName($name) ;

    public function delete($id);

    public function getAllUsers() ;

    public function getUserStatistics() ;
    
    public function search(array $data) ;
    
    
}
