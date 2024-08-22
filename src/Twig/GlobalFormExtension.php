<?php
namespace App\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;
use App\Service\GlobalFormService;
use App\Entity\User;

class GlobalFormExtension extends AbstractExtension
{
    private $globalFormService;

    public function __construct(GlobalFormService $globalFormService)
    {
        $this->globalFormService = $globalFormService;
    }

    public function getFunctions()
    {
        return [
            new TwigFunction('global_form', [$this, 'getGlobalForm']),
        ];
    }

    public function getGlobalForm()
    {
        $user = new User();
        $form = $this->globalFormService->createForm($user);
        return $form->createView();
    }
}