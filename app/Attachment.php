<?php
/**
 * Author: Xavier Au
 * Date: 3/6/2016
 * Time: 1:26 PM
 */

namespace App;


use App\Enums\AttachmentType;
use App\Enums\TemplateType;

class Attachment
{
    public $type;
    public $payload=[];

    /**
     * Attachment constructor.
     * @param $payload
     * @param $type
     */
    public function __construct()
    {
        $this->type = AttachmentType::$image;
        if($this->type == AttachmentType::$image){
            $payload['url']="";
        }elseif($this->type == AttachmentType::$template){
            $payload['template_type']= TemplateType::$generic;
        }

    }

}