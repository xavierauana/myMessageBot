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
    public $object;
    public $entryId;
    public $entryTime;
    public $senderId;
    public $recipientId;
    public $objectTime;
    public $messageTime;
    public $messageId;
    public $messageSeq;
    public $message;


    public function __construct(Request $request) {
        $this->entryId = $this->getEntryId($request);
        $this->entryTime = $this->getEntryTime($request);
        $this->message = $this->getMessage($request);
        $this->messageId = $this->getMessageId($request);
        $this->messageSeq = $this->getMessageSeq($request);
        $this->messageTime = $this->getMessageTime($request);
        $this->object = $this->getObject($request);
        $this->recipientId = $this->getRecipientId($request);
        $this->senderId = $this->getSenderId($request);
    }

    private function getEntryId($request)
    {
        return $request->get('entry')[0]['id'];
    }

    private function getEntryTime($request)
    {
        return $request->get('entry')[0]['time'];
    }

    private function getMessage($request)
    {
        return $request->get('entry')[0]['messaging'][0]['message']['text'];
    }

    private function getMessageId($request)
    {
        return $request->get('entry')[0]['messaging'][0]['message']['mid'];
    }

    private function getMessageSeq($request)
    {
        return $request->get('entry')[0]['messaging'][0]['message']['seq'];
    }

    private function getMessageTime($request)
    {
        return $request->get('entry')[0]['messaging'][0]['timestamp'];
    }

    private function getObject($request)
    {
        return $request->get('object');
    }

    private function getRecipientId($request)
    {
        return $request->get('entry')[0]['messaging'][0]['recipient']['id'];
    }

    private function getSenderId($request)
    {
        return $request->get('entry')[0]['messaging'][0]['sender']['id'];
    }


}