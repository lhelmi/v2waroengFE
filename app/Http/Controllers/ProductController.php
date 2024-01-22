<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ProductService;

class ProductController extends Controller
{

    public function __construct(){
        $this->service = new ProductService();
    }
    private $service;

    public function show($param)
    {

        $data = $this->service->showProduct($param);

        // if(count($data)){
        //     return response()->json([
        //         'message' => 'product',
        //         'data' => $data
        //     ], 200);
        // }

        return $data;

    }

    public function create()
    {
        return view('product.create');
    }
}
