<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ProgrammeCustomerAvailable extends Constraint
{
    public $message = "This customer is not available for programme date time!";

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
