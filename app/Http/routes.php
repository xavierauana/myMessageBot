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

use App\Enums\AttachmentType;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use App\Request as Req;
use Illuminate\Support\Facades\Log;
use Psr\Http\Message\ResponseInterface;

const VERIFY_TOKEN = "B45ycLXmHCqDNuPwybTZCYoInhHEiAJbuGkZC2kwVv4eC41ISgYcNmkKpZC5W9Tl3sASpQZDZD";
const PAGE_TOKEN = "EAAWKZCVRmZBbwBAHgw5elHvQj3jzHrYpKqkrcfLi1FfZCpHN21T15gQZCEZBmiaGcLzIzuzbHJ0ZAJ89GSSLq49yWPaadEk4ZAXnbZAV8JOEo811fwkKrlRe4Dd4Sp1aNt80SP3Q907HmYbkf7wgm9MZC9PFInrHqn1DaWJxXGY5EDAZDZD";
const MESSAGE_URL = "https://graph.facebook.com/v2.6/me/messages";

/**
 * @param                          $incomingMessage
 */
function replyMessage($incomingMessage)
{
    $query = http_build_query(['access_token' => PAGE_TOKEN]);
    $uri = MESSAGE_URL . "?$query";

    Log::info($uri);

    $senderId = $incomingMessage->senderId;

    $data = new Req($senderId, "image", "https://www.nasa.gov/sites/default/files/styles/image_card_4x3_ratio/public/thumbnails/image/leisa_christmas_false_color.png?itok=Jxf0IlS4");

    $options = [
        'content-type' => "application/json; charset=utf8",
        'query'        => ['access_token' => PAGE_TOKEN]
    ];

    $body = json_encode($data);

    $client = new \GuzzleHttp\Client();

    $request = new \GuzzleHttp\Psr7\Request("POST", $uri, $options, $body);

    $promise = $client->sendAsync($request)->then(
        function (ResponseInterface $response) {
            Log::info(serialize($response->getStatusCode()));
        },
        function (RequestException $response) {
            Log::info(serialize($response->getMessage()));
        });

    $promise->wait();
}


Route::get('/', function () {
    return "message bot";
});

// Main
Route::post("/webhook", function (Request $request) {

    Log::info(serialize($request->all()));
    $event = new \App\Entity\MessengerEvent($request);
    $incomingMessages = $event->getIncomingMessage();

    if (count($incomingMessages)) {
        foreach ($incomingMessages as $message) {
            Log::info(serialize($message));
            replyMessage($message);
        }
    }

    return response(null, 200);
});

// Verify webhook connection
Route::get("/webhook", function (Request $request) {
    if ($request->get("hub_verify_token") === VERIFY_TOKEN) {
        return $request->get("hub_challenge");
    }

    return "Error, wrong validation token";
});
