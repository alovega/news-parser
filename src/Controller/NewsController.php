<?php
// src/Controller/NewsController.php
namespace App\Controller;

use App\Entity\News;
use Doctrine\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\NewsRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Message\NewsSchedule;
use App\Form\NewsFormType;
use Faker\Factory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Messenger\MessageBusInterface;

class NewsController extends AbstractController
{

    private $newsRepository;
    private $em;

    public function __construct(NewsRepository $newsRepository, EntityManagerInterface $em){
        $this->newsRepository = $newsRepository;
        $this->em = $em;  
    }
    /**
     * @Route("/downloaded/news", "name: 'view_news'")
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
    
        $generator  = Factory::create("fr_FR");
        $data = [
            'title'=> $generator->title, 
            'description' => $generator->realText(200),
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

    /**
     * @Route("/uploaded", "name: 'uploaded_news'")
     */
    public function index():Response {
        $news = $this->newsRepository->findBy([]);
        return $this->render(
            "news/news.html.twig",
            ["news"=>$news]
        );
    }

    /**
     * @Route("/news/edit/{id}", "name: 'show_movie'")
     */
    public function show($id):Response {
        $news = $this->newsRepository->find($id);
        return $this->render(
            "news/show.html.twig",
            ["news"=>$news]
        );
    }
    
    /**
     * @Route("/news/create", "name: 'create_movie'")
     */
    public function create(Request $request):Response {
        $news = new News();
        $form = $this->createForm(NewsFormType::class, $news);
        $form -> handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $newNews = $form->getData();

            try{

                $this->em->persist($newNews);
                $this->em->flush();
                return $this->redirectToRoute('app_news_index');

            }catch(Exception $e){
                return new Response(["error occured: {$e->getMessage()}"]);
            }
        
        }
        return $this->render(
            'news/create.html.twig',
            ['form' => $form->createView()]
        );
    }
}
?>