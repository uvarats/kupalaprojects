<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;

class SettingsController extends AbstractController
{
    #[Route('/settings', name: 'app_settings')]
    #[IsGranted('IS_AUTHENTICATED_FULLY')]
    public function index(#[CurrentUser] User $user): Response
    {
        $form = $this->createForm(UserType::class, $user);

        return $this->render('settings/index.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
