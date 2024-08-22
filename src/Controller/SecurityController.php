<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Psr\Log\LoggerInterface;



class SecurityController extends AbstractController
{
    #[Route(path: '/login', name: 'app_login')]
    public function login(Request $request, AuthenticationUtils $authenticationUtils, LoggerInterface $logger): Response
    {

        if ($this->getUser()) {
            if ($request->isXmlHttpRequest()) {
                return new JsonResponse(['success' => true, 'redirect_url' => $this->generateUrl('app_profile')]);
            }
            return $this->redirectToRoute('app_profile');
        }

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        if ($request->isXmlHttpRequest()) {
            if ($error) {
                return new JsonResponse(['success' => false, 'message' => $error->getMessageKey()], 400);
            }

            return new JsonResponse(['success' => true, 'message' => 'Login successful']);
        }

        return $this->redirectToRoute('home');
    }

    #[Route(path: '/logout', name: 'app_logout')]
    public function logout(): void
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }
}


