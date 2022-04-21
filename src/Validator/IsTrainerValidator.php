<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class IsTrainerValidator extends ConstraintValidator
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function validate($value, Constraint $constraint)
    {
        if (is_null($value)) {
            return;
        }

        if (!$value instanceof User) {
            throw new UnexpectedTypeException($value, User::class);
        }

        if (!$constraint instanceof IsTrainer) {
            throw new UnexpectedTypeException($constraint, IsTrainer::class);
        }

        if ($this->userRepository->isTrainer($value->getId())) {
            return;
        }

        $this->context->buildViolation($constraint->message)->addViolation();
    }
}
