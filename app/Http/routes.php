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

Route::get('/', function () {
    return view('welcome');
});

Route::post("/webhook", function(Request $request){
    Log::info(serialize($request->all()));
    return response(null, 200);
});

Route::get("/webhook", function(Request $request){
    if($request->get("hub_verify_token") === VERIFY_TOKEN){
        return $request->get("hub_challenge");
    }
    return "Error, wrong validation token";
});
