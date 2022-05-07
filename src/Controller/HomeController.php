<?php

namespace App\Controller;

use App\Entity\User;
use App\Repository\UserRepository;
use App\Service\SerializerService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\ExceptionEvent;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class HomeController extends AbstractController
{
    private SerializerService $serializer;
    private Security $security;


    public function __construct(SerializerService $serializer, Security $security)
    {
        $this->serializer = $serializer;
        $this->security = $security;
    }


    #[IsGranted('ROLE_USER', message: "Vous n'avez pas le droit d'être ici", statusCode: 403)]
    #[Route('/account', name: 'account')]
    public function account(UserRepository $userRepository, Request $request,): Response
    {

        $user = $this->security->getUser();
        $userId = $user-
        dump($user);

//        $content = $this->getUser();
//        $serializedContent = $this->serializer->SimpleSerializer($content, 'json');
//        if (array_key_exists('id', $content)) {
//            $id =
//        }
//        $array = object_to_array($serializedContent);
//
//        dump($content);
//        dump($array);


//        $request->getSession()->set(Security::LAST_USERNAME, $username);


//        $content = $response->toArray();


////
//        $user = $this->getUser();
//        $userRole = $user->getRoles();
//
//        if ($user && $userRole = 'ROLE_USER') {
//            $this->event->setResponse(new RedirectResponse($this->redirectToRoute('app_access_denied_handler')));
//        } else {
//            dump('c de la merde putain de sa mere fdp');
//        }


        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',

        ]);
    }
}
