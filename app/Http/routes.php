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

const VERIFY_TOKEN = "this_is_xavier";

Route::get('/', function () {
    return view('welcome');
});

Rotue::post("/webhook", function(Request $request){
    if($request->has("hub.verify_token") === VERIFY_TOKEN){
        return $request->get("hub.challenge");
    }
    return "Error, wrong validation token";
});
