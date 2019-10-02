<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function showProduct($title, $id){
        $product = $this->marketService->getProduct($id);
        // dd($product);
        return view('products.show')->with([
            'product' => $product,
        ]);
    }
}
