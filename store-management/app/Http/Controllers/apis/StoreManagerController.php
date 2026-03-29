<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\BoxResource;
use App\Http\Resources\BoxSelectionResource;
use App\Http\Resources\LotResource;
use App\Http\Resources\TransferCardResource;
use App\Services\StoreManagerService;
use Illuminate\Http\Request;



class StoreManagerController extends Controller
{
    public function __construct(protected StoreManagerService $storeManagerService) {}






public function dashboard(Request $request)
    {
        $locationCode = $request->user()->location_code;

        return response()->json([
            'status' => 'success',
            'message' => 'Store manager dashboard retrieved successfully',
            'result' => $this->storeManagerService->getDashboard($locationCode),
        ]);
    }

    public function onHoldTransfers(Request $request)
    {
        $locationCode = $request->user()->location_code;

        $transfers = $this->storeManagerService->getOnHoldTransfers(
            $locationCode,
            $request->query('search')
        );

        return response()->json([
            'status' => 'success',
            'message' => 'On hold transfers retrieved successfully',
            'result' => TransferCardResource::collection($transfers),
        ]);
    }

    public function verifiedTransfers(Request $request)
    {
        $locationCode = $request->user()->location_code;

        $transfers = $this->storeManagerService->getVerifiedTransfers(
            $locationCode,
            $request->query('search')
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Verified transfers retrieved successfully',
            'result' => TransferCardResource::collection($transfers),
        ]);
    }

    public function boxes(Request $request)
    {
        $locationCode = $request->user()->location_code;

        $boxes = $this->storeManagerService->getBoxes($locationCode);

        return response()->json([
            'status' => 'success',
            'message' => 'Boxes retrieved successfully',
            'result' => BoxResource::collection($boxes),
        ]);
    }

    public function inTransitLots(Request $request)
    {
        $locationCode = $request->user()->location_code;

        $lots = $this->storeManagerService->getInTransitLots($locationCode);

        return response()->json([
            'status' => 'success',
            'message' => 'In-transit lots retrieved successfully',
            'result' => LotResource::collection($lots),
        ]);
    }

    public function deliveredLots(Request $request)
    {
        $locationCode = $request->user()->location_code;

        $lots = $this->storeManagerService->getDeliveredLots($locationCode);

        return response()->json([
            'status' => 'success',
            'message' => 'Delivered lots retrieved successfully',
            'result' => LotResource::collection($lots),
        ]);
    }






    public function markReceivedByCode(Request $request)
    {
      
        $data=$request->all();

        $result = $this->storeManagerService->markLotReceivedByCode(
            $data['lot_code'],
            $request->user()->id,
            $data['receipt_notes'] ?? null
        );

        return response()->json([
            'status' => 'success',
            'message' => 'LOT marked as received successfully',
            'result' => $result,
        ]);
    }

    public function confirmReceipt(string $lotId, Request $request)
    {

        $data=$request->all();

        $this->storeManagerService->confirmReceipt(
            $lotId,
            $request->user()->id,
            $data['receipt_notes'] ?? null
        );

        return response()->json([
            'status' => 'success',
            'message' => 'LOT receipt confirmed successfully',
        ]);
    }





public function availableBoxesForLot(Request $request)
{
    $data=$request->all() ;

    $locationCode = $request->user()->location_code;

    $boxes = $this->storeManagerService->getAvailableBoxesForLot(
        $locationCode,
        $data['destination']??"",
        $data['search'] ?? null
    );

    return response()->json([
        'status' => 'success',
        'message' => 'Available boxes retrieved successfully',
        'result' => BoxSelectionResource::collection($boxes),
    ]);
}


public function lots(Request $request)
{
    $locationCode = $request->user()->location_code;

    $lots = $this->storeManagerService->getLots($locationCode);

    return response()->json([
        'status' => 'success',
        'message' => 'Lots retrieved successfully',
        'result' => LotResource::collection($lots),
    ]);
}





    
}




