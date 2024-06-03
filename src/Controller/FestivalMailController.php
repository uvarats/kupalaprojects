<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Festival;
use App\Entity\FestivalMail;
use App\Feature\Festival\Repository\FestivalMailRepository;
use App\Feature\Festival\Service\FestivalMailService;
use App\Security\Voter\FestivalVoter;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

#[IsGranted('IS_AUTHENTICATED_FULLY')]
final class FestivalMailController extends AbstractController
{
    public function __construct(
        private readonly FestivalMailService $mailService,
    ) {}

    #[Route('/festival/{id}/mail/{page}', name: 'app_festival_mail')]
    public function index(
        Festival $festival,
        FestivalMailRepository $mailRepository,
        int $page = 1,
    ): Response {
        $this->denyAccessUnlessGranted(FestivalVoter::IS_ORGANIZATION_COMMITTEE_MEMBER, $festival);
        $query = $mailRepository->getFestivalMailQuery($festival);

        $pager = new Pagerfanta(
            new QueryAdapter($query)
        );

        $pager->setMaxPerPage(50)
            ->setCurrentPage($page);

        return $this->render('festival/mail/mails.html.twig', [
            'festival' => $festival,
            'mails' => $pager,
        ]);
    }

    #[Route('/festival/{id}/mail/{mail_id}/view', name: 'app_festival_mail_view')]
    public function viewMail(
        Festival $festival,
        #[MapEntity(mapping: ['mail_id' => 'id'])]
        FestivalMail $mail
    ): Response {
        return $this->render('festival/mail/view_mail.html.twig', [
            'mail' => $mail,
        ]);
    }

    #[Route('/festival/{id}/new-mail', name: 'app_festival_mail_new')]
    public function newMail(
        Festival $festival,
    ): Response {
        $this->denyAccessUnlessGranted(FestivalVoter::IS_ORGANIZATION_COMMITTEE_MEMBER, $festival);

        return $this->render('festival/mail/new_mail.html.twig', [
            'festival' => $festival,
        ]);
    }
}
