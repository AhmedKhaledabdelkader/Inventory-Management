<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\BoxSelectionResource;
use App\Http\Resources\LotResource;
use App\Services\LotService;
use Illuminate\Http\Request;

class LotController extends Controller
{

    public function __construct(protected LotService $lotService) {
       
    }

   

    public function store(Request $request)
{
    $data=$request->all() ;

    $lot = $this->lotService->createLot(
        $data['box_ids'],
        $data['destination'],
        $request->user()->id
    );

    return response()->json([
        'status' => 'success',
        'message' => 'LOT created successfully',
        'result' => new LotResource($lot),
    ]);
}




public function index(Request $request){


  $locationCode = $request->user()->location_code;


    $lots=$this->lotService->getAllLots($locationCode) ;


    return response()->json([

        "status"=>"success",
        "message"=>"lots retrieved successfully",
        "result"=>LotResource::collection($lots)

    ]);



}



public function indexAvaliableBoxes(Request $request){

$locationCode = $request->user()->location_code;

$boxes=$this->lotService->getAvaliableBoxes($locationCode,$request->all()) ;

        return response()->json([

            'status' => 'success',
            'message' => 'avaliable boxes retrieved successfully',
            'result' => BoxSelectionResource::collection($boxes),

        ]) ;




}



    
}
