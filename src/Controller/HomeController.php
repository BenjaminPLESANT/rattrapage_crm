<?php

namespace App\Controller;

use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Serializer\SerializerInterface;


class HomeController extends AbstractController
{
    private UserRepository $user;
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em, UserRepository $user)
    {
        $this->em = $em;
        $this->user = $user;
    }


    #[IsGranted('ROLE_USER', message: "Vous n'avez pas le droit d'être ici", statusCode: 403)]
    #[Route('/account', name: 'account')]
    public function account(Request $request, SerializerInterface $serializer, SessionInterface $session, RouterInterface $router): Response
    {
        $user = $this->getUser();

        $actualDate = new \DateTime('now', new \DateTimeZone('Europe/Paris'));
        $tokenAccessAtTimeStamp = $user->getAccessTokenAt();
        $interval = date_diff($actualDate, $tokenAccessAtTimeStamp);

        if ($user->getIsTokenRevoked() == true) {

            $user->setIsTokenRevoked(false);
            $user->setIsTokenValid(true);
            $user->setAccessToken(rtrim(strtr(base64_encode(random_bytes(32)), '+/', '-_'), '='));
            $user->setAccessTokenAt(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
            $this->em->persist($user);
            $this->em->flush();

        } else {
            if ($interval->i > 30) {

                $user->setIsTokenRevoked(true);
                $user->setIsTokenValid(false);
                $user->setRefreshToken(null);
                $user->setRefreshTokenAt(null);
                $this->em->persist($user);
                $this->em->flush();

                return new RedirectResponse($router->generate('app_logout'));

            } elseif ($interval->i <= 30) {

                $user->setAccessTokenAt(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
                $user->setRefreshToken(true);
                $user->setRefreshTokenAt(new \DateTime('now', new \DateTimeZone('Europe/Paris')));
                $this->em->persist($user);
                $this->em->flush();
            }
        }


        return $this->render('home/index.html.twig', ['controller_name' => 'HomeController',]);
    }


}
