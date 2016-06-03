<?php
/**
 * Author: Xavier Au
 * Date: 3/6/2016
 * Time: 2:00 PM
 */

namespace App\Message;


use Exception;

class Text
{
    public $text;
    private $maxChar = 320;

    /**
     * TextMessage constructor.
     * @param $text
     */
    public function __construct($text)
    {
        if(strlen($text) >0 and strlen($text)< $this->maxChar){
            $this->text = $text;
        }else{
            throw new Exception("Text is too long!");
        }
    }


}