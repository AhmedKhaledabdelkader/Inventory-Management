<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Http\Resources\DroppedTransferResource;
use App\Http\Resources\TransferCardResource;
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


        $transfers=$this->transferService->getHoldTransfers($request->all()) ;

        return response()->json([

            'status' => 'success',
            'message' => 'Transfers retrieved successfully',
            'result' => TransferCardResource::collection($transfers),


        ]) ;

    }

    public function indexPreparedTransfers(Request $request){

        $transfers=$this->transferService->getPreparedTransfers($request->all()) ;

        return response()->json([

            'status' => 'success',
            'message' => 'Prepared transfers retrieved successfully',
            'result' => TransferCardResource::collection($transfers),

        ]) ;
    }


    public function indexDroppedTransfers(Request $request){

        $transfers=$this->transferService->getDroppedTransfers($request->all()) ;

        return response()->json([

            'status' => 'success',
            'message' => 'Dropped transfers retrieved successfully',
            'result' => TransferCardResource::collection($transfers),

        ]) ;

    }

     public function prepare(string $id)
    {
        $this->transferService->prepareTransfer($id);

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


    public function getSummary(){

    
        $summary=$this->transferService->getSummary() ;

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





}
