<?php

namespace App\Services;
use App\Traits\ConsumesExternalServices;

class Marketservice
{
    use ConsumesExternalServices;

    protected $baseUri;

    public function __construct()
    {
        $this->baseUri = config('services.market.base_uri');
    }

    public function makeRequest()
    {

    }
    
    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        
    }

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