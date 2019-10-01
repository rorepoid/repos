<?php

namespace App\Traits;
use App\Services\MarketService;

trait InteractWithMarketResponses{
    
    public function decodeResponse($response)
    {
        $decodeResponse = json_decode($response);
        
        return $decodeResponse->data ?? $decodeResponse;
    }

    public function checkIfErrorResponse($response)
    {
        if(isset($response->error)){
            throw new \Exception("Somethig falied: {$response->error}");
        }
    }
    
}