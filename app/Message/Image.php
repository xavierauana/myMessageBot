<?php
/**
 * Author: Xavier Au
 * Date: 3/6/2016
 * Time: 2:09 PM
 */

namespace App\Message;


class Image
{
    public $attachment;
    public $payload=[];

    /**
     * Template constructor.
     * @param string $imageUrl
     */
    public function __construct(string $imageUrl)
    {
        $this->attachment['type'] = 'image';
        $this->payload['url'] = $imageUrl;
    }
}