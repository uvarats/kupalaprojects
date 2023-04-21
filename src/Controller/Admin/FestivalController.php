<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Festival;
use App\Form\FestivalType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/manage')]
#[IsGranted('ROLE_ADMIN')]
final class FestivalController extends AbstractController
{
    #[Route('/festivals', name: 'app_admin_festivals_manage')]
    public function manageFestivals(): Response
    {
        return $this->render('admin/festival/index.html.twig');
    }

    #[Route('/festival/create', name: 'app_admin_festival_create')]
    public function createFestival(Request $request): Response
    {
        $festival = new Festival();
        $form = $this->createForm(FestivalType::class, $festival);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            dd($form->getData());
        }

        return $this->render('admin/festival/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
