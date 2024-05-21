<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Festival;
use App\Entity\FestivalMail;
use App\Entity\User;
use App\Feature\Festival\Dto\CreateFestivalMail;
use App\Feature\Festival\Dto\FestivalMailData;
use App\Feature\Festival\Service\FestivalMailService;
use App\Form\FestivalMailType;
use App\Message\SendFestivalMail;
use App\Repository\FestivalMailRepository;
use App\Security\Voter\FestivalVoter;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Pagerfanta\Pagerfanta;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\CurrentUser;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\UX\Turbo\TurboBundle;

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
        #[CurrentUser]
        User $user,
        Request $request,
    ): Response {
        $this->denyAccessUnlessGranted(FestivalVoter::IS_ORGANIZATION_COMMITTEE_MEMBER, $festival);

        $mail = new FestivalMailData();

        $form = $this->createForm(FestivalMailType::class, $mail, ['festival' => $festival]);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $createRequest = $this->composeRequest($mail, $festival, $user);
            $this->mailService->process($createRequest);

            if ($request->getPreferredFormat() === TurboBundle::STREAM_FORMAT) {
                $request->setRequestFormat(TurboBundle::STREAM_FORMAT);

                return $this->render('festival/mail/mail_sent_stream.html.twig', [
                    'festival' => $festival,
                    'mail' => $mail,
                ]);
            }

            return $this->redirectToRoute('app_festival_mail', [
                'id' => $festival->getId(),
            ]);
        }

        return $this->render('festival/mail/new_mail.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    private function composeRequest(FestivalMailData $mailData, Festival $festival, User $user): CreateFestivalMail
    {
        return new CreateFestivalMail(
            festival: $festival,
            author: $user,
            subject: $mailData->getSubject(),
            content: $mailData->getContent(),
            recipients: $mailData->getRecipients(),
        );
    }

}
