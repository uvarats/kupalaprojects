<?php

declare(strict_types=1);

namespace App\ValueResolver;

use App\Entity\Interface\ProjectAuthorInterface;
use App\Entity\User;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final readonly class ProjectAuthorResolver implements ValueResolverInterface
{
    public function __construct(
        private Security $security,
    ) {}

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        $argumentType = $argument->getType();
        /** @var User $currentUser */
        $currentUser = $this->security->getUser();
        if (
            $currentUser === null ||
            $argumentType === null ||
            !is_subclass_of($argumentType, ProjectAuthorInterface::class)
        ) {
            return [];
        }

        return [$currentUser->getProjectAuthor()];
    }
}
