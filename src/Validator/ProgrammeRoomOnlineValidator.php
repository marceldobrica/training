<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Programme;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ProgrammeRoomOnlineValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof Programme) {
            throw new UnexpectedTypeException($value, Programme::class);
        }

        if (!$constraint instanceof ProgrammeRoomOnline) {
            throw new UnexpectedTypeException($constraint, ProgrammeRoomOnline::class);
        }

        if (is_null($value->getRoom()->getBuilding()) === $value->isOnline) {
            return;
        }

        $this->context->buildViolation($constraint->message)->addViolation();
    }
}
