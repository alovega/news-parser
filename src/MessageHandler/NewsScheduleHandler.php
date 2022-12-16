<?php

namespace App\MessageHandler;

use App\Message\NewsSchedule;
use App\Entity\News;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;
use Doctrine\Persistence\ManagerRegistry;

#[AsMessageHandler]
class NewsScheduleHandler implements MessageHandlerInterface
{
    private $doctrine;

    public function __construct(ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
    }
    public function __invoke(NewsSchedule $message)
    {
        // ... do some work - like sending an SMS message!
        $data = json_decode($message->getContent());
        $entityManager = $this->doctrine -> getManager();
        $news = $this->doctrine->getRepository(News::class)->findOneBy(['title'=>$data->title]);
        print_r($news);
        if($news == null){
            $news = new News();
        }

        /** @var title $title */
        $title = $data->title;
        /** @var description $description */
        $description = $data->description;
        $image = $data->image;

        //persist data
        $news->setTitle($title);
        $news->setDescription($description);
        $news->setImageUrl($image);
        $entityManager->persist($news);
        $entityManager->flush();
        
    }
}