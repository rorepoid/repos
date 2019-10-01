<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class WelcomeController extends Controller
{
    function showWelcomePage()
    {
        $products = $this->marketService->getProducts();
        dd($products);
        return view('welcome')->with([
            'products' => $products,
            ]);
    }
}
