<?php
namespace App\Message;

class NewsSchedule
{
    private $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function getContent()
    {
        return $this->data;
    }
    
}