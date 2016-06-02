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

use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
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

    $messageData = createMessageData();
    $senderId = $incomingMessage->senderId;

    $data = [
        "recipient" => ["id" => $senderId],
        "message"   => $messageData
    ];

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

function createMessageData(): array
{
    return [
        "attachment" => [
            "type"    => "template",
            "payload" => [
                "template_type" => "generic",
                "elements"      => [
                    [
                        "title"     => "First card",
                        "subtitle"  => "Element #1 of an hscroll",
                        "image_url" => "http=>//messengerdemo.parseapp.com/img/rift.png",
                        "buttons"   => [
                            [
                                "type"  => "web_url",
                                "url"   => "https://www.messenger.com/",
                                "title" => "Web url"
                            ],
                            [
                                "type"    => "postback",
                                "title"   => "Postback",
                                "payload" => "Payload for first element in a generic bubble",
                            ]
                        ]
                    ],
                    [
                        "title"     => "Second card",
                        "subtitle"  => "Element #2 of an hscroll",
                        "image_url" => "http://messengerdemo.parseapp.com/img/gearvr.png",
                        "buttons"   => [
                            [
                                "type"    => "postback",
                                "title"   => "Postback",
                                "payload" => "Payload for second element in a generic bubble",
                            ]
                        ],
                    ]
                ]
            ]
        ]
    ];
}


Route::get('/', function () {
    return "message bot";
});

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

Route::get("/webhook", function (Request $request) {
    if ($request->get("hub_verify_token") === VERIFY_TOKEN) {
        return $request->get("hub_challenge");
    }

    return "Error, wrong validation token";
});
