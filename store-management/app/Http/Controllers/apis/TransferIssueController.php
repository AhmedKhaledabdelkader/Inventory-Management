<?php

namespace App\Http\Controllers\apis;

use App\Http\Controllers\Controller;
use App\Services\TransferIssueService;
use Illuminate\Http\Request;


class TransferIssueController extends Controller
{
    public function __construct(
        protected TransferIssueService $transferIssueService
    ) {}

    public function reportIssue(string $id, Request $request)
    {

        $result = $this->transferIssueService->reportIssueAndDrop(
            $id,
            $request->all(),
            $request->user()->id
        );

        if (!$result) {
            
             return response()->json(['status'=>'error','message'=>'Transfer not found'],404);
        }

        if ($result=='verify') {
            
             return response()->json(['status'=>'error','message'=>'Verified transfer cannot be dropped.'],400);

        }

        return response()->json([
            'status' => 'success',
            'message' => 'Issue reported and transfer dropped successfully',
            'result' => $result,
        ]);
    }
}
