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
    public $messageId = null;
    public $messageSeq = null;
    public $message = null;
    private $messagingEvents = [];


    public function __construct(Request $request) {
        $this->messagingEvents =   $request->get('entry')[0]['messaging'];

        $this->entryId = $this->getEntryId($request);
        $this->entryTime = $this->getEntryTime($request);
        $this->object = $this->getObject($request);

        if($this->isIncomingMessage()){
            $this->message = $this->getMessage();
            $this->messageId = $this->getMessageId();
            $this->messageSeq = $this->getMessageSeq();
            $this->messageTime = $this->getMessageTime();
        }
        $this->recipientId = $this->getRecipientId();
        $this->senderId = $this->getSenderId();
    }

    private function getEntryId($request)
    {
        return $request->get('entry')[0]['id'];
    }

    private function getEntryTime($request)
    {
        return $request->get('entry')[0]['time'];
    }

    private function getMessage()
    {
        return $this->messagingEvents[0]['message']['text'];
    }

    private function getMessageId()
    {
        return $this->messagingEvents[0]['message']['mid'];
    }

    private function getMessageSeq()
    {
        return $this->messagingEvents[0]['message']['seq'];
    }

    private function getMessageTime()
    {
        return $this->messagingEvents[0]['timestamp'];
    }

    private function getObject($request)
    {
        return $request->get('object');
    }

    private function getRecipientId()
    {
        return $this->messagingEvents[0]['recipient']['id'];
    }

    private function getSenderId()
    {
        return $this->messagingEvents[0]['sender']['id'];
    }

    private function isIncomingMessage()
    {
        return isset($this->messagingEvents[0]['message']) and isset($this->messagingEvents[0]['message']['text']);
    }


}