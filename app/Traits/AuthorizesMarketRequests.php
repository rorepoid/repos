<?php

namespace App\Traits;
use App\Services\MarketService;
use App\Services\MarketAuthenticationService;

trait AuthorizesMarketRequests{
    
    public function resolveAuthorization(&$queryParams, &$formParams, &$headers)
    {
        $accessToken = $this->resolveAccessToken();
        $headers['Authorization'] = $accessToken;
    }

    public function resolveAccessToken()
    {
        $authorizationService = resolve(MarketAuthenticationService::class);
        return $authorizationService->getClientCredentialsToken();
    }
    
}