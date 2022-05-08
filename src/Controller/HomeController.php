<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Serializer\SerializerInterface;


class HomeController extends AbstractController
{




    #[IsGranted('ROLE_USER', message: "Vous n'avez pas le droit d'être ici", statusCode: 403)]
    #[Route('/account', name: 'account')]
    public function account(UserRepository $userRepository, Request $request, SerializerInterface $serializer): Response
    {
        $user = $this->getUser();
        $entityAsArray = $serializer->normalize($user, 'array');

        dump($entityAsArray);
        dump($user);


        return $this->render('home/index.html.twig', ['controller_name' => 'HomeController',]);
    }
}
