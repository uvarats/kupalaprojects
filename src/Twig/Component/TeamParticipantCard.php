<?php

declare(strict_types=1);

namespace App\Twig\Component;

use App\Entity\Participant;
use App\Entity\TeamParticipant;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\UX\LiveComponent\Attribute\AsLiveComponent;
use Symfony\UX\LiveComponent\Attribute\LiveProp;
use Symfony\UX\LiveComponent\DefaultActionTrait;

#[AsLiveComponent]
final class TeamParticipantCard extends AbstractController
{
    use DefaultActionTrait;

    #[LiveProp]
    public TeamParticipant $participant;

    #[LiveProp]
    public bool $isInKickFlow = false;

    public function kick() {}
    public function confirmKick() {}
    public function refuseKick() {}
}
