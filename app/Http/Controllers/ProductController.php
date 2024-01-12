<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function __construct(){}

    public function show($code)
    {
        $data = $this->getProduct($code);


        if(count($data)){
            return response()->json([
                'message' => 'product',
                'data' => $data
            ], 200);
        }

        return response()->json([
            'message' => 'not found',
            'data' => null
        ], 404);

    }

    public function create()
    {
        return view('product.create');
    }
}
