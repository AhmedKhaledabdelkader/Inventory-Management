<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\LotResource;
use App\Services\RunnerService;
use Illuminate\Http\Request;



class RunnerController extends Controller
{

    public function __construct(protected RunnerService $runnerService) {}




 public function dashboard(Request $request)
    {
        return response()->json([
            'status' => 'success',
            'message' => 'Runner dashboard retrieved successfully',
            'result' => $this->runnerService->getDashboard($request->user()->id),
        ]);
    }

    public function myLots(Request $request)
    {
        $lots = $this->runnerService->getRunnerLots(
            $request->user()->id,
            $request->query('status')
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Runner LOTs retrieved successfully',
            'result' => LotResource::collection($lots),
        ]);
    }

    public function pickup(Request $request)
    {
       $data=$request->all();

        $result = $this->runnerService->pickupByCode(
            $request->user()->id,
            $data['lot_code']
        );

        return response()->json([
            'status' => 'success',
            'message' => 'LOT picked up successfully',
            'result' => $result,
        ]);
    }

    public function updateStatus(Request $request, string $lotId)
    {
       $data=$request->all() ;

        $this->runnerService->updateStatusManually(
            $request->user()->id,
            $lotId,
            $data['status']
        );

        return response()->json([
            'status' => 'success',
            'message' => 'LOT status updated successfully',
        ]);
    }

    public function deliver(Request $request, string $lotId)
    {

        $data=$request->all() ;

        $this->runnerService->markAsDelivered($data,$request->user()->id, $lotId);

        return response()->json([
            'status' => 'success',
            'message' => 'LOT marked as delivered successfully',
        ]);
    }















    
}
