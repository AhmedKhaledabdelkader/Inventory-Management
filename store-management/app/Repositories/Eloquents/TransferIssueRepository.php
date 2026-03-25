<?php

namespace App\Repositories\Eloquents;

use App\Models\TransferIssue;
use App\Repositories\Contracts\TransferIssueRepositoryInterface;

class TransferIssueRepository implements TransferIssueRepositoryInterface
{
   
    public TransferIssue $model ;

    public function __construct(TransferIssue $transfer_issue) {

        $this->model = $transfer_issue;
    
    }



    
     public function create(array $data)
    {
        return $this->model->create($data);
    }





}
