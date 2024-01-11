<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {

    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */

    private function getProduct($barcode){
        $res = [];
        $list = [
            [
                "barcode" => "9786024412845",
                "name" => "The Great Expectation",
                "price" => "120000"
            ],
            [
                "barcode" => "9786020327037",
                "name" => "The Children of Hurin",
                "price" => "140000"
            ],
            [
                "barcode" => "9786020322698",
                "name" => "Silmarillion",
                "price" => "250000"
            ]
        ];
        foreach ($list as $key => $value) {
            if($value['barcode'] == $barcode){
                $res = [
                    'barcode' => $value['barcode'],
                    "name" => $value['name'],
                    "price" => $value['price']
                ];

                break;
            }
        }
        return $res;
    }

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
}
