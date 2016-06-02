<?php
/**
 * Author: Xavier Au
 * Date: 2/6/2016
 * Time: 7:12 PM
 */

namespace App\Entity;


use Illuminate\Http\Request;

class MessengerEvent
{
    private $event;
    private $request;
    private $incomingMessage = [];

    /**
     * MessengerEvent constructor.
     * @param \Illuminate\Http\Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->event = $this->request->get('entry')[0]['messaging'];
    }

    public function getIncomingMessage()
    {
        $this->parseIncomingMessages();
        return $this->incomingMessage;
    }

    private function parseIncomingMessages()
    {
        for($i = 0; $i<count($this->event); $i++){
            if($this->isIncomingMessageEvent($this->event[$i])){
                $this->incomingMessage[] = new IncomingMessage($this->event, $i);
            }
        }
    }

    private function isIncomingMessageEvent($event)
    {
        return isset($event['message']) and isset($event['message']['text']);
    }
}