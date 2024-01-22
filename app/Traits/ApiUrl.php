<?php

namespace App\Traits;

use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;

trait ApiUrl
{
    public function list()
    {
        return [
            'show_product' => 'api/public/product?params='
        ];
    }

    public function isOnTheList($urlName){
        $list = $this->list();

        if(array_key_exists($urlName, $list)){
            $url = API_URL . $list[$urlName];

            return $url;
        }

        throw new HttpResponseException(response([
            "error" => 'Url Internal Not Found',
            'internal' => true
        ], 404));
    }

    function getRequest($url, $headers = [])
    {
        $sessionToken = $this->getTokenSession();
        $req = Http::withToken($sessionToken)->withHeaders($headers)->get($url);
        // createLog(null, $url, 'GET', json_encode($req->json()));
        $req = $this->validateTokenAPI($req);
        return $req;
    }

    function postRequestPublic($url, $headers = [])
    {
        return Http::withHeaders($headers)->get($url);
    }

    function validateTokenAPI($response)
    {
        if ($response->status() == 401) {
            Session::flush();
            Session::regenerate();
        }

        return $response;
    }

    function getTokenSession()
    {
        $token = session()->get(API_CREDENTIAL);
        if(!$token){
            Session::flush();
            Session::regenerate();
            return false;
        }
        return $token['data']['auth']['token'];
    }
}

?>
