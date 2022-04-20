<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ProgrammeBuildingTime extends Constraint
{
    public string $message = 'If programme is not Online, start time and end time should be in Building working hours';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
