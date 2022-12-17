<?php
// src/Controller/NewsController.php
namespace App\Controller;

use App\Entity\News;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Message\NewsSchedule;
use GuzzleHttp\Client;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;

class NewsController extends AbstractController
{

    /**
     * @Route("/downloaded/news")
     * 
     */
    public function downloadedNews()
    {

        $number = random_int(0, 100);

        return $this->render('news/news.html.twig', [
            'number' => $number,
        ]);

    }

    /**
     * @Route("/news", "name: 'create_news'")
     * simulate a call to an external api for news data
     */
    public function getNews(Request $request): Response
    {
    
        // $title = 'Stranger'.uniqid();
        // $description = 'Random Stranger'.uniqueId();
        // $image = 'https://images.unsplash.com/photo-1670881298357-1792768792d6?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80';

        $data = [
            'title'=> 'Stranger'.'-'.uniqid(), 
            'description' => 'Random Stranger'.'-'.uniqid(),
            'image' => 'https://images.unsplash.com/photo-1670881298357-1792768792d6?ixlib=rb-4.0.3&ixid=MnwxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8&auto=format&fit=crop&w=1470&q=80'
        ];

        $response = new Response(json_encode($data), 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;

    }

    /**
     * @Route("/schedule", "name: 'queue_news'")
     */
    public function schedule(MessageBusInterface $bus, Request $request){
        $message = new NewsSchedule(json_decode($request->getContent()));
        var_dump("this is controller");
        var_dump(json_decode($request->getContent()));
        // $request->request->all();
        $bus->dispatch($message);
        return new Response(json_encode($message->getContent()), 200);
    }
}
?>