<?php

namespace App\Services;
use App\Traits\ConsumesExternalServices;
use App\Traits\AuthorizesMarketRequests;
use App\Traits\InteractWithMarketResponses;

class Marketservice
{
    use ConsumesExternalServices, AuthorizesMarketRequests, InteractWithMarketResponses;

    protected $baseUri;

    public function __construct()
    {
        $this->baseUri = config('services.market.base_uri');
    }

    
}