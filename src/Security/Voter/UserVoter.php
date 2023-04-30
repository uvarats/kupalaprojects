<?php

namespace App\Security\Voter;

use App\Entity\User;
use App\Repository\FestivalRepository;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

final class UserVoter extends Voter
{
    public const IS_RELATED_WITH_ANY_FESTIVAL = 'IS_RELATED_WITH_ANY_FESTIVAL';

    public function __construct(private readonly FestivalRepository $festivalRepository) {

    }

    protected function supports(string $attribute, mixed $subject): bool
    {
        return $attribute === self::IS_RELATED_WITH_ANY_FESTIVAL;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }
        assert($user instanceof User);

        return match($attribute) {
            self::IS_RELATED_WITH_ANY_FESTIVAL => $this->isRelatedWithAnyFestival($user),
        };
    }

    private function isRelatedWithAnyFestival(User $user): bool
    {
        return $this->festivalRepository->isUserRelatedWithAnyFestival($user);
    }
}
