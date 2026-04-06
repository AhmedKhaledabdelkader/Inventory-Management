<?php

namespace App\Services;

use App\Repositories\Contracts\UserRepositoryInterface;
use App\Repositories\Eloquents\LotRepository;
use Exception;

class RunnerManagerService
{
    public function __construct(protected LotRepository $lotRepository,protected UserRepositoryInterface $userRepository) {
      
    }



public function assignRunnerToLot(string $lotId, ?string $runnerId)
{
    $lot = $this->lotRepository->findById($lotId);

    if (!$lot) {
        throw new Exception('LOT not found');
    }


    $this->lotRepository->assignRunner($lotId, $runnerId);

    return true;
}


public function getDashboardRunnerManagerScreen(){


$statistics=$this->lotRepository->getDashboardStats() ;

return $statistics ;

}



public function getAllRunnerswithAssignedLots(){


$runners=$this->lotRepository->getLotsPerRunner() ;

return $runners ;

}


public function getAllLotsWithRunners(?string $search){


    $lots=$this->lotRepository->getAllWithRunner($search??"") ;


    return $lots ;


}


public function getAllRunners() {


    $runners=$this->userRepository->getAllRunners() ;

    return $runners ;
    
}







}
