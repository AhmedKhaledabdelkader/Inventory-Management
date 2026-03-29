<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\BoxResource;
use App\Services\BoxService;
use Illuminate\Http\Request;


class BoxController extends Controller
{
    
     public function __construct(
        protected BoxService $boxService
      
    ) {}

   
    public function create(Request $request)
    {
       $data=$request->all() ;

        $boxes = $this->boxService->createBoxes(
            $data['transfer_id'],
            $data['destination'],
            (int) $data['number_of_boxes'],
            $request->user()->id
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Boxes created successfully',
            'result' => BoxResource::collection($boxes),
        ]);
    }


      public function index(Request $request)
    {
   
            $locationCode = $request->user()->location_code;


        $boxes = $this->boxService->getAllBoxes($locationCode);

        return response()->json([
            'status' => 'success',
            'message' => 'Boxes retrieved successfully',
            'result' => BoxResource::collection($boxes),
        ]);
    }

}
