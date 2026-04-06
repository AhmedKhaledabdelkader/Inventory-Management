<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\IdNameRunnerResource;
use App\Http\Resources\LotResource;
use App\Http\Resources\RunnerResource;
use App\Services\RunnerManagerService;
use Illuminate\Http\Request;


class RunnerManagerController extends Controller
{

    public function __construct(protected RunnerManagerService $runnerManagerService) {
        
    }


    public function assign(Request $request, string $lotId)
{
   
    $data=$request->all() ;

    $this->runnerManagerService->assignRunnerToLot($lotId,$data["runner_id"]??null);

    return response()->json([
        'status' => 'success',
        'message' => 'Runner assigned to the lot successfully',
    ]);
}

public function getDashboard(Request $request){


$result=$this->runnerManagerService->getDashboardRunnerManagerScreen();

return response()->json([

    'status'=>'success',
    'message'=>'retrieving dashboard runner manager screen',
    'result'=>$result 

]);



}


public function index(Request $request)
{
    $lots = $this->runnerManagerService->getAllLotsWithRunners($request->query('search'));

    return response()->json([
        'status' => 'success',
        'message'=>'retrieving all lots with runner successfully',
        'result' => LotResource::collection($lots),
    ]);
}



public function indexAllRunnersWithAssignedLots()
{
    $runners = $this->runnerManagerService->getAllRunnerswithAssignedLots();

    return response()->json([
        'status' => 'success',
        'message'=>'retrieving all runners with assigned lots success',
        'result' =>RunnerResource::collection($runners),
    ]);
}


public function indexAllRunners()
{
    $runners = $this->runnerManagerService->getAllRunners();

    return response()->json([
        'status' => 'success',
        'message'=>'retrieving all runners successfully',
        'result' =>IdNameRunnerResource::collection($runners),
    ]);

}




}
