<?php

declare(strict_types=1);

namespace App\Controller\Team;

use App\Entity\Team;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/account/teams/{id}/archive', name: 'app_account_team_archive')]
final class ArchiveTeam extends AbstractController
{
    public function __invoke(Team $team): Response
    {
        return $this->render('account/team/archive.html.twig', ['team' => $team]);
    }
}
