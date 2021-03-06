<?php

namespace App\Services;
use App\Traits\ConsumesExternalServices;
use App\Traits\AuthorizesMarketRequests;
use App\Traits\InteractWithMarketResponses;

class MarketAuthenticationService
{
    
    protected $baseUri;
    protected $clientId;
    protected $clientSecret;
    protected $passwordclientId;
    protected $passwordclientSecret;
    
    use ConsumesExternalServices, InteractWithMarketResponses;

    public function __construct()
    {
        $this->baseUri = config('services.market.base_uri');
        $this->clientId = config('services.market.client_id');
        $this->clientSecret = config('services.market.client_secret');
        $this->passwordclientId = config('services.market.password_client_id');
        $this->passwordclientSecret = config('services.market.password_client_secret');
    }

    public function getClientCredentialsToken()
    {
        if ($token = $this->existingValidClientCredentialsToken()) {
            return $token;
        }
        $formParams = [
            'grant_type' => 'client_credentials',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
        ];
        $tokenData = $this->makeRequest('POST', 'oauth/token', [], $formParams);

        $this->storeValidToken($tokenData, 'client_credentials');

        return $tokenData->accessToken;
    }

    /**
     * Genera la url para el botón de autorización
     * 
     * @return string
     */
    public function resolveAuthorizationUrl()
    {
        $query = \http_build_query([
            'client_id' => $this->clientId,
            'redirect_uri' => route('authorization'),
            'response_type' => 'code',
            'scope' => 'purchase-product manage-products manage-account read-general',
        ]);

        return "{$this->baseUri}/oauth/authorize?{$query}";
    }

    /**
     * Obtiene un Access Token desde un código dado
     * 
     * @param string $token
     * @return stdClass
     */
    public function getCodeToken($code)
    {
        $formParams = [
            'grant_type' => 'authorization_code',
            'client_id' => $this->clientId,
            'client_secret' => $this->clientSecret,
            'redirect_uri' => route('authorization'),
            'code' => $code,
        ];
        $tokenData = $this->makeRequest('POST', 'oauth/token', [], $formParams);

        $this->storeValidToken($tokenData, 'authorization_code');

        return $tokenData->access_token;
    }

    public function getPasswordToken($username, $password)
    {
        $formParams = [
            'grant_type' => 'password',
            'client_id' => $this->passwordClientId,
            'client_secret' => $this->passwordClientSecret,
            'username' => $username,
            'password' => $password,
            'scope' => 'purchase-product manage-products manage-account read-general',
        ];
        $tokenData = $this->makeRequest('POST', 'oauth/token', [], $formParams);

        $this->storeValidToken($tokenData, 'password');

        return $tokenData->access_token;
    }

    /**
     * Stores a valid token
     * @param stdClass $tokenData 
     * @param string $grantType 
     * @return void
     */
    public function storeValidToken($tokenData, $grantType)
    {
        $tokenData->token_expires_at = now()->addSeconds($tokenData->expires_in - 5);

        $tokenData->accessToken = "{$tokenData->token_type} {$tokenData->access_token}";

        $tokenData->grant_type = $grantType;

        session()->put(['current_token' => $tokenData]);
    }

    /**
     * Verifies if any token existing
     * @return string|boolean
     */
    public function existingValidClientCredentialsToken()
    {
        if (session()->has('current_token')) {
            $tokenData = session()->get('current_token');

            if (now()->lt($tokenData->token_expires_at)) {
                return $tokenData->access_token;
            }
        }
        return false;
    }

}