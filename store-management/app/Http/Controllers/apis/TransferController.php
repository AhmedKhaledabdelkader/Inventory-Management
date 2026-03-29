<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\DroppedTransferResource;
use App\Http\Resources\TransferCardResource;
use App\Http\Resources\VerifiedTransferSelectionResource;
use App\Services\TransferService;
use Illuminate\Http\Request;



class TransferController extends Controller
{

    public $transferService ;

    public function __construct(TransferService $transferService) {
        $this->transferService = $transferService;
    }

    public function sync()
    {
        $result = $this->transferService->syncHoldTransfersFromWarehouse();

        return response()->json([

            'status' => 'success',
            'message' => 'Transfers synced successfully',
            'result' => $result,
        ]);

    }


    public function  indexHoldTransfers(Request $request){


        $locationCode = $request->user()->location_code;

        $transfers=$this->transferService->getHoldTransfers($locationCode,$request->all()) ;

        return response()->json([

            'status' => 'success',
            'message' => 'Transfers retrieved successfully',
            'result' => TransferCardResource::collection($transfers),


        ]) ;

    }

    public function indexPreparedTransfers(Request $request){

          $locationCode = $request->user()->location_code;

        $transfers=$this->transferService->getPreparedTransfers($locationCode,$request->all()) ;

        return response()->json([

            'status' => 'success',
            'message' => 'Prepared transfers retrieved successfully',
            'result' => TransferCardResource::collection($transfers),

        ]) ;
    }


    public function indexDroppedTransfers(Request $request){

          $locationCode = $request->user()->location_code;

        $transfers=$this->transferService->getDroppedTransfers($locationCode,$request->all()) ;

        return response()->json([

            'status' => 'success',
            'message' => 'Dropped transfers retrieved successfully',
            'result' => TransferCardResource::collection($transfers),

        ]) ;

    }

    
    public function indexVerifiedTransfers(Request $request){


        
          $locationCode = $request->user()->location_code;

        $transfers=$this->transferService->getVerifiedTransfers($locationCode,$request->all()) ;

        return response()->json([

            'status' => 'success',
            'message' => 'verified transfers retrieved successfully',
            'result' => VerifiedTransferSelectionResource::collection($transfers),

        ]) ;

    }

     public function prepare(Request $request,string $id)
    {


        $user=$request->user() ;

        $this->transferService->prepareTransfer($id,$user->id);

        return response()->json([

             'status' => 'success',
            'message' => 'Transfer marked as prepared successfully',
        ]);
    }


      public function drop(Request $request,string $id)
    {
        
        $this->transferService->dropTransfer($id,$request->input('reason'));

        return response()->json([
             'status' => 'success',
            'message' => 'Transfer marked as dropped successfully'
        ]);
    }


    public function getSummary(Request $request){

    
         $locationCode = $request->user()->location_code;
    
        $summary=$this->transferService->getSummary($locationCode) ;

        return response()->json([

             'status' => 'success',
             'message'=>'summary retrieved successfully',
             'result'=>$summary
            


        ]);




    }



    public function droppedTransfersSummary()
{
    $data = $this->transferService->getDroppedTransfersSummary();

    return response()->json([
        'status' => 'success',
        'message' => 'Dropped transfers summary retrieved successfully',
        'data' => [
            'dropped_transfers_count' => $data['dropped_transfers_count'],
            'all_transfers_count' => $data['all_transfers_count'],
            'dropped_transfers' => DroppedTransferResource::collection($data['dropped_transfers']),
        ],
    ]);
}



public function findTransfer(Request $request,string $id){


    $transfer=$this->transferService->findTransfer($id);

    if (!$transfer) {
    
       return response()->json([

        'status'=>'error',
        'message'=>'Transfer not found'
        
       ],404);
    
    }

        return response()->json([


        'status'=>'success',
        'message'=>'transfer retrieved successfully',
        'result'=>new TransferCardResource($transfer) 


        ]);


}

 public function verifyManual(string $id, Request $request)
    {
        $validated = $request->validate([
            'notes' => ['nullable', 'string'],
        ]);

        $this->transferService->verifyTransferManually(
            $id,
            $validated['notes'] ?? null
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Transfer verified manually successfully',
        ]);
    }


     public function reject(string $id, Request $request)
    {
        $validated = $request->validate([
            'notes' => ['nullable', 'string'],
        ]);

        $this->transferService->rejectTransfer(
            $id,
            $validated['notes'] ?? null
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Transfer rejected successfully',
        ]);
    }






     public function scan(string $id, Request $request)
    {
        $data=$request->all() ;

        $result = $this->transferService->scanBarcode(
            $id,
            $data['barcode']
        );

        return response()->json([
            'status' => 'success',
            'message' => 'Barcode scanned successfully',
            'result' => $result,
        ]);
    }


}
