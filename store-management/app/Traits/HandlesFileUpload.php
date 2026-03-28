<?php

namespace App\Traits;

use App\Services\ImageConverterService;
use Illuminate\Support\Facades\Storage;

trait HandlesFileUpload
{

     public function uploadFile($file, string $folder, ImageConverterService $imageConverterService)
    {
        if (!empty($file)) {
            return $imageConverterService->convertAndStore($file, $folder);
        }

        return null;
    }



    public function updateFile($newFile, ?string $oldFilePath, string $folder, ImageConverterService $imageConverterService, string $disk = 'private')
    {
        if (!empty($newFile)) {
            
            if ($oldFilePath && Storage::disk($disk)->exists($oldFilePath)) {
                Storage::disk($disk)->delete($oldFilePath);
            }

            
            return $imageConverterService->convertAndStore($newFile, $folder);
        }

        return $oldFilePath;
    }




    public function deleteFile(?string $filePath, string $disk = 'private'): bool
    {
        if ($filePath && Storage::disk($disk)->exists($filePath)) {
            return Storage::disk($disk)->delete($filePath);
        }

        return false;
    }



    public function uploadContentFile($file,$folder){

         if (!empty($file)) {

        $imagePath=$file->store($folder,"private");
        
    return $imagePath ;

        }


    return null ;


    }

    public function updateContentFile($newFile, ?string $oldFilePath, string $folder){


        if (!empty($newFile)) {
            if ($oldFilePath && Storage::disk('private')->exists($oldFilePath)) {
                Storage::disk('private')->delete($oldFilePath);
            }
            
            return $newFile->store($folder, 'private');
        }

        return $oldFilePath ;

    }


    public function deleteContentFile(?string $filePath){

         if ($filePath && Storage::disk('private')->exists($filePath)) {
            Storage::disk('private')->delete($filePath);
        }

        return false ;
        
    }

    
}
