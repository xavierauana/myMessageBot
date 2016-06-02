<?php
/**
 * Author: Xavier Au
 * Date: 2/6/2016
 * Time: 5:21 PM
 */

namespace App\Entity;


use Illuminate\Http\Request;

class IncomingMessage
{
    private $index;
    public $object;
    public $entryId;
    public $entryTime;
    public $senderId;
    public $recipientId;
    public $objectTime;
    public $messageTime;
    public $messageId = null;
    public $messageSeq = null;
    public $message = null;
    private $messagingEvents = [];


    public function __construct(Request $request, $index = 0)
    {
        $this->index = $index;
        $this->messagingEvents = $request->get('entry')[$index]['messaging'];

        $this->entryId = $this->getEntryId($request);
        $this->entryTime = $this->getEntryTime($request);
        $this->object = $this->getObject($request);

        $this->message = $this->getMessage();
        $this->messageId = $this->getMessageId();
        $this->messageSeq = $this->getMessageSeq();
        $this->messageTime = $this->getMessageTime();
        $this->recipientId = $this->getRecipientId();
        $this->senderId = $this->getSenderId();
    }

    private function getEntryId($request)
    {
        return $request->get('entry')[$this->index]['id'];
    }

    private function getEntryTime($request)
    {
        return $request->get('entry')[$this->index]['time'];
    }

    private function getMessage()
    {
        return $this->messagingEvents[$this->index]['message']['text'];
    }

    private function getMessageId()
    {
        return $this->messagingEvents[$this->index]['message']['mid'];
    }

    private function getMessageSeq()
    {
        return $this->messagingEvents[$this->index]['message']['seq'];
    }

    private function getMessageTime()
    {
        return $this->messagingEvents[$this->index]['timestamp'];
    }

    private function getObject($request)
    {
        return $request->get('object');
    }

    private function getRecipientId()
    {
        return $this->messagingEvents[$this->index]['recipient']['id'];
    }

    private function getSenderId()
    {
        return $this->messagingEvents[$this->index]['sender']['id'];
    }

}