<?php

declare(strict_types=1);

namespace App\Controller\Account;

use App\Entity\User;
use App\Feature\Account\Form\UserSettingsType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/settings', name: 'app_settings')]
#[IsGranted('IS_AUTHENTICATED_FULLY')]
class SettingsController extends AbstractController
{
    public function __invoke(#[CurrentUser] User $user): Response
    {
        $form = $this->createForm(UserSettingsType::class, $user);

        return $this->render('settings/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
