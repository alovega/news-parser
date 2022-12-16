<?php
namespace App\Message;

class NewsSchedule
{
    private $data;
    // private $image_url;
    // private $description;
    // private $date;

    public function __construct(array $data)
    {
        $this->data = json_encode($data);
    }

    public function getContent()
    {
        return $this->data;
    }
    
}