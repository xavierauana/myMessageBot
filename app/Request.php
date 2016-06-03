<?php
/**
 * Author: Xavier Au
 * Date: 3/6/2016
 * Time: 1:15 PM
 */

namespace App;


use App\Enums\NotificationType;
use App\Factory\MessageFactory;

class Request
{
    public $recipient;
    public $message;
    public $notification_type;

    private $availableMessageType = [
      'text', 'image', 'template'
    ];

    /**
     * Request constructor.
     * @param string $recipientId
     * @param string $type
     * @param        $options
     */
    public function __construct(string $recipientId, string $type, $options)
    {
        $this->recipient['id'] = $recipientId;
        if(in_array(strtolower($type), $this->availableMessageType)){
            $this->message = MessageFactory::create($type, $options);
            $this->notification_type = NotificationType::$REGULAR;
        }
    }
    
    
}