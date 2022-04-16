<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Programme;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class ProgrammeDateTimeNotInPastValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$constraint instanceof ProgrammeDateTimeNotInPast) {
            throw new UnexpectedTypeException($constraint, ProgrammeDateTimeNotInPast::class);
        }

        if (
            $value > new \DateTime()
        ) {
            return;
        }

        $this->context->buildViolation($constraint->message)->addViolation();
    }
}
