<?php 

namespace App\Service;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Security\Core\Security;
use App\Entity\User;
use App\Form\RegistrationFormType;

class GlobalFormService
{
    private $formFactory;
    private $security;

    public function __construct(FormFactoryInterface $formFactory, Security $security)
    {
        $this->formFactory = $formFactory;
        $this->security = $security;
    }

    public function createForm(User $user)
    {
        return $this->formFactory->create(RegistrationFormType::class, $user);
    }
}
