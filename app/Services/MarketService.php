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

    /**
     * Obtains the list of products from the API
     * @return stdClass
     */
    public function getProducts()
    {
        return $this->makeRequest('GET', 'products');
    }

    /**
     * Obtains the list of categories from the API
     * @return stdClass
     */
    public function getCategories()
    {
        return $this->makeRequest('GET', 'categories');
    }

    /**
     * Obtains a product from the API
     * * @param int $id
     * @return stdClass
     */
    public function getProduct($id)
    {
        return $this->makeRequest('GET', "products/{$id}");
    }

    /**
     * Obtains a category from the API
     * * @param int $id
     * @return stdClass
     */
    public function getCategoryProducts($id)
    {
        return $this->makeRequest('GET', "categories/{$id}/products");
    }
    
    /**
     * Obtains a user information from the API
     * * @param int $id
     * @return stdClass
     */
    public function getUserInformation()
    {
        return $this->makeRequest('GET', "users/me");
    }
}