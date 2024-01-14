<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public static function getResponse($data, $code, $message){
        $arrayCode = array();

        // Information
        $arrayCode[100] = 'Continue';
        $arrayCode[101] = 'Switching protocols';
        $arrayCode[102] = 'Processing';
        $arrayCode[103] = 'Early Hints';

        // Success
        $arrayCode[200] = 'OK';
        $arrayCode[201] = 'Created';
        $arrayCode[202] = 'Accepted';
        $arrayCode[203] = 'Non-Authoritative Information';
        $arrayCode[204] = 'No Content';
        $arrayCode[205] = 'Reset Content';
        $arrayCode[206] = 'Partial Content';
        $arrayCode[207] = 'Multi-Status';
        $arrayCode[208] = 'Already Reported';

        // Diversion
        $arrayCode[300] = 'Multiple Choices';
        $arrayCode[301] = 'Moved Permanently';
        $arrayCode[302] = 'Found (Previously "Moved Temporarily")';
        $arrayCode[303] = 'See Other';
        $arrayCode[304] = 'Not Modified';
        $arrayCode[305] = 'Use Proxy';
        $arrayCode[306] = 'Switch Proxy';
        $arrayCode[307] = 'Temporary Redirect';
        $arrayCode[308] = 'Permanent Redirect';

        // Error
        $arrayCode[400] = 'Bad Request';
        $arrayCode[401] = 'Unauthorized';
        $arrayCode[402] = 'Payment Required';
        $arrayCode[403] = 'Forbidden';
        $arrayCode[404] = 'Not Found';
        $arrayCode[405] = 'Method Not Allowed';
        $arrayCode[406] = 'Not Acceptable';
        $arrayCode[407] = 'Proxy Authentication Required';
        $arrayCode[408] = 'Request Timeout';
        $arrayCode[409] = 'Conflict';
        $arrayCode[410] = 'Gone';
        $arrayCode[411] = 'Length Required';
        $arrayCode[412] = 'Precondition Failed';
        $arrayCode[413] = 'Payload Too Large';
        $arrayCode[414] = 'URI Too Long';
        $arrayCode[415] = 'Unsupported Media Type';
        $arrayCode[416] = 'Range Not Satisfiable';
        $arrayCode[417] = 'Expectation Failed';
        $arrayCode[421] = 'Misdirected Request';
        $arrayCode[422] = 'Unprocessable Entity';
        $arrayCode[423] = 'Locked';
        $arrayCode[424] = 'Failed Dependency';
        $arrayCode[425] = 'Too Early';
        $arrayCode[426] = 'Upgrade Required';
        $arrayCode[428] = 'Precondition Required';
        $arrayCode[429] = 'Too Many Requests';

        $arrayCode[500] = 'Internal Server Error';
        $arrayCode[501] = 'Not Implemented';
        $arrayCode[502] = 'Bad Gateway';
        $arrayCode[503] = 'Service Unavailable';
        $arrayCode[504] = 'Gateway Timeout';
        $arrayCode[505] = 'HTTP Version Not Supported';
        $arrayCode[506] = 'Variant Also Negotiates';
        $arrayCode[507] = 'Insufficient Storage';
        $arrayCode[508] = 'Loop Detected';
        $arrayCode[509] = 'Bandwidth Limit Exceeded';
        $arrayCode[510] = 'Not Extended';

        if (array_key_exists($code, $arrayCode)) {
            return response()->json([
                'timestamp' => date('Y-m-d H:i:s'),
                'code' => $code,
                'status' => $arrayCode[$code],
                'message' => $message,
                'data' => $data,

            ], $code);
        } else {
            return response()->json([
                'code' => 400,
                'message' => 'Kode API tidak ditemukan'
            ]);
        }
    }
}
