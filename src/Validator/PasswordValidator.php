<?php

namespace App\Validator;

use Symfony\Component\HttpFoundation\File\Exception\UnexpectedTypeException;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class PasswordValidator extends ConstraintValidator
{
    public function validate($value, Constraint $constraint): void
    {
        if (!$constraint instanceof Password) {
            throw new UnexpectedTypeException($constraint, Password::class);
        }

        if (
            ($value === trim($value)) && (strpos($value, ' ') === false) &&
            preg_match('/^(?=.*?[A-Z])(?=.*?[#?!@$%^&*-]).{8,}$/', $value, $matches)
        ) {
            return;
        }

        $this->context->buildViolation($constraint->message)->addViolation();
    }
}
