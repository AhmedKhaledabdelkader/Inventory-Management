<?php

namespace App\Http\Controllers\Apis;

use App\Http\Controllers\Controller;
use App\Services\MediaService;
use Illuminate\Http\Request;



class MediaController extends Controller
{

    public $mediaService ;


    public function __construct(MediaService $mediaService) {
        

        $this->mediaService = $mediaService ;

    }

    public function show($filename)
    {
         $media = $this->mediaService->showMedia($filename);

        if (!$media) {
            return response()->json([
                'status' => 'error',
                'message' => 'No media file provided'
            ], 400);
        }

       
        return response()->file($media, [
            'Content-Type' => mime_content_type($media),
        ]);

        
    }






    
}
