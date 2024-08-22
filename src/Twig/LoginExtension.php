<?php

namespace App\Twig;

use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Twig\Extension\AbstractExtension;
use Twig\Extension\GlobalsInterface;

class LoginExtension extends AbstractExtension implements GlobalsInterface
{
    private $authenticationUtils;

    public function __construct(AuthenticationUtils $authenticationUtils)
    {
        $this->authenticationUtils = $authenticationUtils;
    }

    public function getGlobals(): array
    {
        return [
            'last_username' => $this->authenticationUtils->getLastUsername(),
            'error' => $this->authenticationUtils->getLastAuthenticationError(),
        ];
    }
}
