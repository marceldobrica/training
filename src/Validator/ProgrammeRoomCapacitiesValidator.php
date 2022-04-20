<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Programme;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Exception\UnexpectedTypeException;

class ProgrammeRoomCapacitiesValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof Programme) {
            throw new UnexpectedTypeException($value, Programme::class);
        }

        if (!$constraint instanceof ProgrammeRoomCapacities) {
            throw new UnexpectedTypeException($constraint, ProgrammeRoomCapacities::class);
        }

        if ($value->getRoom()->capacity >= $value->maxParticipants) {
            return;
        }

        $this->context->buildViolation($constraint->message)->addViolation();
    }
}
