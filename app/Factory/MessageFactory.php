<?php
/**
 * Author: Xavier Au
 * Date: 3/6/2016
 * Time: 1:48 PM
 */

namespace App\Factory;



use App\Message\Image;
use App\Message\Text;

class MessageFactory
{
    /**
     * MessageFactory constructor.
     */
    public static function create($type, $options)
    {
        $factory = new self;
        switch(strtolower($type)){
            case "text":
                return $factory->createTextMessage($options);
                break;
            case "image":
                return $factory->createImageMessage($options);
                break;
            case "template":
                return $factory->createTemplateMessage($options);
                break;
        }
    }

    private function createTextMessage($options){
        if(is_array($options))
            $options = implode(",",$options);
        return new Text($options);
    }

    private function createImageMessage($options){
        if(is_array($options))
            $options = implode(",",$options);

        return new Image($options);
    }

    private function createTemplateMessage($options){
        return "template";
    }
}