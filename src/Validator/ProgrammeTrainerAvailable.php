<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ProgrammeTrainerAvailable extends Constraint
{
    public $message = "This trainer is not available for programme date time!";

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
