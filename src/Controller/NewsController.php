<?php
// src/Controller/NewsController.php
namespace App\Controller;

use App\Entity\News;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

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
     */
    public function createNews(ManagerRegistry $doctrine, Request $request): JsonResponse
    {
        $entityManager = $doctrine -> getManager();
        $news = $doctrine->getRepository(News::class)->findOneBy(['title'=>$request->request->get('title')]);
        if($news == null){
            $news = new News();
        }
        /** @var title $title */
        $title = $request->request->get('title');
        /** @var description $description */
        $description = $request->request->get('description');
        $date_added = $request->request->get('date_added');
        /** @var uploadedFile $image */
        $image = $request->files->get('image');
        $destination = $this-> getParameter('kernel.project_dir').'/public/uploads/image';
        // get file name extension
        $originalFile = pathinfo($image->getClientOriginalName(),PATHINFO_FILENAME);
        $name = $title.'-'.$news->getId().'.'.$image->guessExtension();
        //move file to location
        $image -> move($destination, $name);

        //set and save data to the database

        $news->setTitle($title);
        $news->setDescription($description);
        $news->setImageName($name);

        $entityManager->persist($news);
        $entityManager->flush();

        return $this->json(['data'=>'Added new news article with id '.$news->getId(), 'status'=>200]);

    }
}
?>