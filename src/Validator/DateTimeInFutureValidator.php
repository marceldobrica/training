<?php

declare(strict_types=1);

namespace App\Validator;

use App\Entity\Programme;
use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DateTimeInFutureValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint)
    {
        if (!$value instanceof \DateTime) {
            throw new UnexpectedTypeException($value, \DateTime::class);
        }

        if (!$constraint instanceof DateTimeInFuture) {
            throw new UnexpectedTypeException($constraint, DateTimeInFuture::class);
        }

        if (
            $value > new \DateTime()
        ) {
            return;
        }

        $this->context->buildViolation($constraint->message)->addViolation();
    }
}
