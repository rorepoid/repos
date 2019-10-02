<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    function showWelcomePage()
    {
        $products = $this->marketService->getProducts();
        $categories = $this->marketService->getCategories();
        // dd($products, $categories);
        return view('welcome')->with([
                'products' => $products,
                'categories' => $categories,
            ]);
    }
}
