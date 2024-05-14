<?php

declare(strict_types=1);

namespace App\Feature\Team\Validator;

use App\Feature\Core\Collection\EmailCollection;
use App\Feature\Core\ValueObject\Email;
use App\Feature\Team\Repository\TeamInviteRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Exception\UnexpectedValueException;

final class NoPendingInvitesValidator extends ConstraintValidator
{
    public function __construct(
        private readonly TeamInviteRepository $inviteRepository,
    ) {}

    #[\Override]
    public function validate(mixed $value, Constraint $constraint): void
    {
        if (!$constraint instanceof NoPendingInvites) {
            throw new UnexpectedTypeException($constraint, NoPendingInvites::class);
        }

        if ($value === null) {
            return;
        }

        if (!is_array($value)) {
            throw new UnexpectedValueException($value, 'array');
        }

        $emailCollection = new EmailCollection();
        $emails = array_filter($value);
        foreach ($emails as $email) {
            $emailCollection[] = Email::fromString($email);
        }

        $invites = $this->inviteRepository->findByEmails($emailCollection);

        $pendingEmails = [];
        foreach ($invites as $invite) {
            $pendingEmails[] = $invite->getRecipient()->getEmail();
        }

        $pendingEmails = array_unique($pendingEmails);

        foreach ($pendingEmails as $pendingEmail) {
            $this->context->buildViolation($constraint->message)
                ->setParameter('{{ email }}', $pendingEmail)
                ->addViolation();
        }
    }
}
