<?php

namespace App\Controller;

use Symfony\Component\Mime\Email;
use Symfony\Component\Mailer\MailerInterface;
use App\Entity\User;
use App\Security\AppCustomAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use App\Form\RegistrationFormType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;
use Symfony\Component\HttpFoundation\JsonResponse;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register', methods: ['POST'])]
    public function register(Request $request,
    UserPasswordHasherInterface $userPasswordHasher,
    EntityManagerInterface $entityManager, 
    UserAuthenticatorInterface $userAuthenticator, 
    AppCustomAuthenticator $authenticator,
    MailerInterface $mailer): JsonResponse
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // MAKE TOKEN
            $confirmationToken = bin2hex(random_bytes(32));
            $user->setConfirmationToken($confirmationToken);   

            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $entityManager->persist($user);
            $entityManager->flush();

            // MAKE MAIL FOR CONFIMATION
            $confirmationUrl = $this->generateUrl('app_confirm_email', ['token' => $confirmationToken], UrlGeneratorInterface::ABSOLUTE_URL);
            $email = (new Email())
            ->from('cros371@gmail.com')
            ->to($user->getEmail())
            ->subject('Confirmation de l\'adresse email')
            ->html($this->renderView('email/confirm_email.html.twig', [
                'user' => $user,
                'confirmationLink' => $confirmationUrl
            ]));

            $mailer->send($email);

            //IF YOU WAN AUTOMATICLY AUTINTIFICATION
            // $response = $userAuthenticator->authenticateUser(
            //     $user,
            //     $authenticator,
            //     $request
            // );

            // if ($response instanceof JsonResponse) {
            //     return $response;
            // }

            return new JsonResponse(['success' => true]);
        }

        $errors = [];
        foreach ($form->getErrors(true) as $error) {
            $errors[$error->getOrigin()->getName()] = $error->getMessage();
        }

        return new JsonResponse(['errors' => $errors]);
    }

    #[Route('/confirm-email/{token}', name: 'app_confirm_email')]
    public function confirmEmail(string $token, EntityManagerInterface $entityManager): Response
    {
        $user = $entityManager->getRepository(User::class)->findOneBy(['confirmationToken' => $token]);

        if (!$user) {
            return $this->redirectToRoute('home');
        }

        $user->setConfirmationToken(null);
        $user->setEmailConfirmed(true);
        $entityManager->flush();

        return $this->redirectToRoute('app_email_confirmed', ['id' => $user->getId()]);
    }

    #[Route('/email_confirmed/{id}', name: 'app_email_confirmed')]
    public function pageConfirmation (int $id, EntityManagerInterface $entityManager): Response
    {
        // Поиск пользователя по токену
        $user = $entityManager->getRepository(User::class)->findOneBy(['id' => $id]);

        if (!$user) {
            // Если токен неверный
            return $this->redirectToRoute('home');
        }

        // Подтверждение email, удаление токена
        $user->setConfirmationToken(null);
        $user->setEmailConfirmed(true);
        $entityManager->flush();

        return $this->render('security/confirmed.html.twig');
    }


}


