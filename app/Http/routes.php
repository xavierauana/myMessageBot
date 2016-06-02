<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the controller to call when that URI is requested.
|
*/

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

const VERIFY_TOKEN = "B45ycLXmHCqDNuPwybTZCYoInhHEiAJbuGkZC2kwVv4eC41ISgYcNmkKpZC5W9Tl3sASpQZDZD";
const PAGE_TOKEN = "EAAWKZCVRmZBbwBAHgw5elHvQj3jzHrYpKqkrcfLi1FfZCpHN21T15gQZCEZBmiaGcLzIzuzbHJ0ZAJ89GSSLq49yWPaadEk4ZAXnbZAV8JOEo811fwkKrlRe4Dd4Sp1aNt80SP3Q907HmYbkf7wgm9MZC9PFInrHqn1DaWJxXGY5EDAZDZD";
const MESSAGE_URL = "https://graph.facebook.com/v2.6/me/messages";

Route::get('/', function () {

    dd(unserialize('a:2:{s:6:"object";s:4:"page";s:5:"entry";a:1:{i:0;a:3:{s:2:"id";s:16:"1768147536755704";s:4:"time";i:1464856644718;s:9:"messaging";a:1:{i:0;a:4:{s:6:"sender";a:1:{s:2:"id";s:17:"10154043934195239";}s:9:"recipient";a:1:{s:2:"id";s:16:"1768147536755704";}s:9:"timestamp";i:1464856166415;s:7:"message";a:3:{s:3:"mid";s:36:"mid.1464856166394:6e359cd0890102d746";s:3:"seq";i:6;s:4:"text";s:7:"another";}}}}}}  
[2016-06-02 08:37:25] local.INFO: a:2:{s:6:"object";s:4:"page";s:5:"entry";a:1:{i:0;a:3:{s:2:"id";s:16:"1768147536755704";s:4:"time";i:1464856645371;s:9:"messaging";a:2:{i:0;a:4:{s:6:"sender";a:1:{s:2:"id";s:17:"10154043934195239";}s:9:"recipient";a:1:{s:2:"id";s:16:"1768147536755704";}s:9:"timestamp";i:1464856330413;s:7:"message";a:3:{s:3:"mid";s:36:"mid.1464856330405:8fd459117e0126cc73";s:3:"seq";i:7;s:4:"text";s:3:"try";}}i:1;a:4:{s:6:"sender";a:1:{s:2:"id";s:17:"10154043934195239";}s:9:"recipient";a:1:{s:2:"id";s:16:"1768147536755704";}s:9:"timestamp";i:1464856620897;s:7:"message";a:3:{s:3:"mid";s:36:"mid.1464856620889:261ec172250e728f67";s:3:"seq";i:8;s:4:"text";s:12:"anothere try";}}}}}}  
'));
    return view('welcome');
});

Route::post("/webhook", function(Request $request){
    $incomingMessage = new \App\Entity\IncomingMessage($request);
    Log::info(serialize( $incomingMessage ));
    $messageData = "hello";
    $senderId = $incomingMessage->senderId;
    $data = [
        "recipient"=> ["id"=>$senderId],
        "message"=> $messageData
    ];
    $query = http_build_query(['access_token'=>PAGE_TOKEN]);
    $client = new \GuzzleHttp\Client();
    $request = new \GuzzleHttp\Psr7\Request("POST", MESSAGE_URL."?$query",[
        'json'=>$data
    ]);
    $client->sendAsync($request)->then(function($response){
        Log::info(serialize($response));
    });
    return response(null, 200);
});

Route::get("/webhook", function(Request $request){
    if($request->get("hub_verify_token") === VERIFY_TOKEN){
        return $request->get("hub_challenge");
    }
    return "Error, wrong validation token";
});
