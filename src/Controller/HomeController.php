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
        $data = $serializer->normalize($user, 'array');

        dump($data);

        if ($data['isTokenValid']) {
            dump('oui oui oui');
        } else {
            dump('non non non');

        }

        $putain = $request->getSession();
        dump($putain);

        return $this->render('home/index.html.twig', ['controller_name' => 'HomeController',]);
    }

    /**
     * Called on every request to decide if this authenticator should be
     * used for the request. Returning `false` will cause this authenticator
     * to be skipped.
     */
    public function supports(Request $request): bool
    {
        dump('une requête supports');

        $user = $request->getContent();
        dump($user);
        return $request->headers->has('X-AUTH-TOKEN');
    }
}
