<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CategoryProductController extends Controller
{
    /**
     * Returns a page with product from a given category
     * @return \Iluminate\Http\Response
     */
    public function showProducts($title, $id){
        $products = $this->marketService->getCategoryProducts($id);
        // dd($products);
        return view('categories.products.show')->with([
            'products' => $products,
        ]);


    }
}
