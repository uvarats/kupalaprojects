<?php

declare(strict_types=1);

namespace App\ValueResolver;

use App\Attribute\CurrentParticipant;
use App\Entity\Participant;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class ParticipantResolver implements ValueResolverInterface
{
    public function __construct(
        private Security $security,
    ) {}

    #[\Override]
    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $type = $argument->getType();

        if ($type !== Participant::class) {
            return [];
        }

        $attributes = $argument->getAttributesOfType(CurrentParticipant::class);

        if ($attributes === []) {
            return [];
        }

        $currentUser = $this->security->getUser();

        if (!$currentUser instanceof User) {
            return [];
        }

        $participant = $currentUser->getParticipant();

        if ($participant === null) {
            return [];
        }

        return [$participant];
    }
}
