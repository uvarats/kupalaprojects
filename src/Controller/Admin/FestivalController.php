<?php

declare(strict_types=1);

namespace App\Controller\Admin;

use App\Entity\Festival;
use App\Form\FestivalType;
use App\Repository\FestivalRepository;
use Doctrine\ORM\EntityManagerInterface;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[Route('/manage')]
#[IsGranted('ROLE_ADMIN')]
final class FestivalController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager) {

    }

    #[Route('/festivals/{page}', name: 'app_admin_festivals_manage')]
    public function manageFestivals(FestivalRepository $festivalRepository, int $page = 1): Response
    {
        $query = $festivalRepository->createOrderedQuery();

        $pager = new Pagerfanta(
            new QueryAdapter($query),
        );
        $pager->setMaxPerPage(25)
            ->setCurrentPage($page);


        return $this->render('admin/festival/index.html.twig', [
            'festivals' => $pager,
        ]);
    }

    #[Route('/festival/create', name: 'app_admin_festival_create')]
    public function createFestival(Request $request): Response
    {
        $festival = new Festival();
        $form = $this->createForm(FestivalType::class, $festival);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->persist($festival);
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_festivals_manage');
        }

        return $this->render('admin/festival/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/festival/{id}/update', name: 'app_admin_festival_update')]
    public function updateFestival(Festival $festival, Request $request): Response
    {
        $form = $this->createForm(FestivalType::class, $festival);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();

            return $this->redirectToRoute('app_admin_festivals_manage');
        }

        return $this->render('admin/festival/update.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/festival/{id}/remove', name: 'app_admin_festival_remove')]
    public function removeFestival(Festival $festival): Response
    {
        $this->entityManager->remove($festival);
        $this->entityManager->flush();

        return $this->redirectToRoute('app_admin_festivals_manage');
    }
}
