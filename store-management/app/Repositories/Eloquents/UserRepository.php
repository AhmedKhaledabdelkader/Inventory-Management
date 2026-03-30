<?php

namespace App\Repositories\Eloquents;

use App\Models\User;
use App\Repositories\Contracts\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
   
    
public User $model ;

    public function __construct(User $user) {
        $this->model = $user;
    }


    public function create(array $data)
    {
        return $this->model->create($data);
    }

      public function attachRoles(User $user, array $roleIds)
    {
        $user->roles()->sync($roleIds);
    }

      public function findById($id)
    {
        return $this->model->with('roles')->find($id);
    }

    

    public function delete($id)
    {
        $user = $this->model->find($id);
        if ($user) {
            return $user->delete();
        }
        return false;
    }


    public function getAllUsers()
    {
        return $this->model->with('roles')->get();
    }



    public function getUserStatistics()
{
    $totalUsers = $this->model->count();

    $activeUsers = $this->model->where('status', 'active')->count();

    $blockedUsers = $this->model->where('status', 'blocked')->count();

    $administrators = $this->model->whereHas('roles', function ($query) {
        $query->where('name', 'admin');
    })->count();

    return [
        'total_users' => $totalUsers,
        'active_users' => $activeUsers,
        'blocked_users' => $blockedUsers,
        'administrators' => $administrators,
    ];
}


public function search(array $data)
{
    $query = $this->model->query();

    // ---------- Global Search ----------
    if (!empty($data['search'])) {
        $search = $data['search'];

        $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('id', 'like', "%{$search}%")
              ->orWhere('location_code', 'like', "%{$search}%")
              ->orWhereHas('roles', function ($roleQuery) use ($search) {
                  $roleQuery->where('name', 'like', "%{$search}%");
              });
        });
    }

    // ---------- Filters ----------
    if (!empty($data['role_ids'])) {
        $query->whereHas('roles', function ($q) use ($data) {
            $q->whereIn('id', $data['role_ids']);
        });
    }

    if (!empty($data['statuses'])) {
        $query->whereIn('status', $data['statuses']);
    }

    // ---------- Pagination ----------
    $page = $data['page'] ?? 1;
    $size = $data['size'] ?? 10;

    // ---------- Return ----------
    return $query
        ->with(['roles:id,name'])
        ->latest()
        ->paginate($size, ['*'], 'page', $page);
}

public function findByName($name)
{
    return $this->model->where('name', $name)->first();
    
}



public function updatePassword($user, string $hashedPassword)
{
    return $user->update([
        'password' => $hashedPassword,
    ]);
}


}