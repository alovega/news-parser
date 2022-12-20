<?php

namespace App\Controller;
use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    /**
     * @Route("/registration", name="app_registration")
     */
    public function index(UserPasswordHasherInterface $passwordHasher, Request $request, ManagerRegistry $doctrine): Response
    {
        // return $this->render('registration/index.html.twig', [
        //     'controller_name' => 'RegistrationController',
        // ]);
        $entityManager = $doctrine->getManager();
        //ensure password, email is set
        $data = $request->getContent();
        $encoded_data = \json_decode($data);
        if(!$encoded_data->email || !$encoded_data->password){
            $response = new Response("Ensure password and email is set", 400);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
         //ensure no user with email exist
         $user = $doctrine->getRepository(User::class)->findOneBy(['email'=>$encoded_data->email]);
        if($user != null){
            $response = new Response("User with email already exists", 400);
            $response->headers->set('Content-Type', 'application/json');
            return $response;
        }
        $user = new User();
        $email = $encoded_data->email;
        $plaintextPassword = $encoded_data->password;
        $hashedPassword = $passwordHasher->hashPassword(
            $user,
            $plaintextPassword
        );
        //persist data
        $user->setEmail($email);
        $user->setPassword($hashedPassword);
        $entityManager->persist($user);
        $entityManager->flush();
        $response = new Response("Successfully Created user", 200);
        $response->headers->set('Content-Type', 'application/json');
        return $response;
    }
}
