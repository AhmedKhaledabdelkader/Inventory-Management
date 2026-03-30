<?php

namespace App\Services;

use App\Repositories\Contracts\RoleRepositoryInterface;
use App\Repositories\Contracts\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use PhpParser\Node\Expr\FuncCall;
use Symfony\Component\HttpFoundation\Request;

class UserService
{
    public $userRepository,$roleRepository ;

    public function __construct(UserRepositoryInterface $userRepository,RoleRepositoryInterface $roleRepository) {

        $this->userRepository = $userRepository;
        $this->roleRepository = $roleRepository;
    }



    public function createUser(array $data)
    {
        $user=$this->userRepository->create($data);

        if (! empty($data['role_ids'])) {
            $this->userRepository->attachRoles($user, $data['role_ids']);
        }

        return $user->load('roles');


    }


    public function toggleUserStatus(string $userId)
{
    $user = $this->userRepository->findById($userId);

    if (!$user) {
     
        return null ;
    }

    $newStatus = $user->status === 'active' ? 'blocked' : 'active';

    $user->update([
        'status' => $newStatus,
    ]);

    return $user;
}


public function updateUser(string $userId, array $data)
{
    $user = $this->userRepository->findById($userId);

    if (! $user) {
        return null;
    }

    $updateData = [];

    if (isset($data['name'])) {
        $updateData['name'] = $data['name'];
    }

    if (isset($data['email'])) {
        $updateData['email'] = $data['email'];
    }

    if (isset($data['location_code'])) {
        $updateData['location_code'] = $data['location_code'];
    }

    if (isset($data['password']) && $data['password'] !== '') {
        $updateData['password'] = Hash::make($data['password']);
    }

    $user->update($updateData);

    
    if (isset($data['role_ids'])) {
        $this->userRepository->attachRoles($user, $data['role_ids']);
    }

    return $user->load('roles');
}




    public function deleteUser(string $id)
    {
        $user = $this->userRepository->findById($id);
        
        if (!$user) {
            return false;
        }

        $user->roles()->detach();

        return $this->userRepository->delete($id);
    }



    public function getAllRoles()
    {
        return $this->roleRepository->getAllRoles();
    }

 
    public function getUserStatistics()
    {
        return $this->userRepository->getUserStatistics();
    }


    public function searchUsers(array $data)
    {
        return $this->userRepository->search($data);
    }


    
public function authenticateUser(array $data){


$user=$this->userRepository->findByName($data["name"]);


if ($user) {
    
if (Hash::check($data["password"], $user->password)) {



     $token = $user->createToken('api_token')->plainTextToken;

    return [
        'user'  => $user,
        'token' => $token,
    ];
 



}

else{

    return 'invalid username or password' ;

}

}

else{

return 'invalid username or password' ;

}

}




 public function logoutCurrentDevice(Request $request)
    {
        $token = $request->attributes->get('accessToken');

        if ($token) {
            $token->delete();
        }

       // return ['status'=>'success','message' =>  __('logout_current_device_success')];

       return true ;
    }

    /**
     * Logout from all devices
     */
    public function logoutAllDevices($user)
    {
        $user->tokens()->delete();

      //  return ['status'=>'success','message' => __('logout_all_devices_success')];

      return true ;
    }





    public function changePassword($user, array $data)
{
    
    if (!Hash::check($data['current_password'], $user->password)) {
        return [
            'success' => false,
            'message' => 'Current password is incorrect',
        ];
    }

    if ($data['current_password'] === $data['new_password']) {
        return [
            'success' => false,
            'message' => 'New password must be different from current password',
        ];
    }

    $updated = $this->userRepository->updatePassword(
        $user,
        Hash::make($data['new_password'])
    );

    if (!$updated) {
        return [
            'success' => false,
            'message' => 'Password update failed',
        ];
    }

    return [
        'success' => true,
        'message' => 'Password changed successfully',
    ];
}







}
