<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateUserRequest;
use App\Http\Resources\RoleResource;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserStatisticsResource;
use App\Models\Role;
use App\Services\UserService;
use Illuminate\Http\Request;

class UserController extends Controller
{

    public $userService ;

    public function __construct(UserService $userService) {
        $this->userService = $userService;
    }


    public function store(Request $request){
        

    $user=$this->userService->createUser($request->all());

    return response()->json([
        'status'=>'success',
        'message' => 'User created successfully',
        'result' => new UserResource($user)
    ], 201);


    }

        public function toggleStatus(string $userId)
        {
            $user = $this->userService->toggleUserStatus($userId);
    
            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }
    
            return response()->json([
                'status' => 'success',
                'message' => 'User status updated successfully',
                'result' => new UserResource($user)
            ]);
        }


        public function update(Request $request, string $userId)
        {
            $user = $this->userService->updateUser($userId, $request->all());
    
            if (! $user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }
    
            return response()->json([
                'status' => 'success',
                'message' => 'User updated successfully',
                'result' => new UserResource($user)
            ]);
        }




        public function destroy(string $userId)
        {
            $user = $this->userService->deleteUser($userId);
    
            if (! $user) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'User not found'
                ], 404);
            }
    
            return response()->json([
                'status' => 'success',
                'message' => 'User deleted successfully'
            ]);
        }



        public function getAllRoles()
        {
            $roles = $this->userService->getAllRoles();
    
            return response()->json([
                'status' => 'success',
                'message' => 'Roles retrieved successfully',
                'result' => RoleResource::collection($roles)
            ]);
        }


        public function getUserStatistics()
        {
            $statistics = $this->userService->getUserStatistics();
    
            return response()->json([
                'status' => 'success',
                'message' => 'User statistics retrieved successfully',
                'result' => new UserStatisticsResource($statistics)
            ]);
        }

            public function search(Request $request)
            {
                $users = $this->userService->searchUsers($request->all());
        
                return response()->json([
                    'status' => 'success',
                    'message' => 'Users retrieved successfully',
                    'result' => UserResource::collection($users),
                    'currentPage' => $users->currentPage(),
                    'perPage' => $users->perPage(),
                    'total' => $users->total(),
                    'lastPage' => $users->lastPage(),
                ]);
            }

      
    public function login(Request $request){


  $result = $this->userService->authenticateUser($request->all());

           
  if (is_array($result)) {
                return response()->json([
    'status' => 'success',
    'message' =>"login to the system successfully",
    'data' => [
        'user' => [
            'id' => $result['user']['id'],
            'name' => $result['user']['name'],
        ],
        'token' => $result['token'],
        'token_type' => 'Bearer'
    ]
], 200);

            }

            return response()->json([
                'status' => 'error',
                'message' => $result,
            ], 401);

    }


    
public function logout(Request $request)
    {
        $response = $this->userService->logoutCurrentDevice($request);
        
        if ($response) {
            
            return response()->json([

           'status'=>'success',
           'message'=>'logout from the current device successfully'

            ]) ;
        }
    }

    /**
     * Logout from all devices
     */
    public function logoutAll(Request $request)
    {
        $response = $this->userService->logoutAllDevices($request->user());

          
        if ($response) {
            
            return response()->json([

           'status'=>'success',
           'message'=>'logout from the all devices successfully'

            ]) ;
        }
    }



    
}
