<?php
// src/Controller/NewsController.php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class NewsController extends AbstractController
{

    /**
     * @Route("/downloaded/news")
     * 
     */
    public function downloaded_news(){

        $number = random_int(0, 100);

        return $this->render('news/news.html.twig', [
            'number' => $number,
        ]);

    }
}
?>