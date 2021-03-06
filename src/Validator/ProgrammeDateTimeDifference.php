<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ProgrammeDateTimeDifference extends Constraint
{
    public string $message = 'Difference between programmes end date and start date should be greater or equal ' .
                              'with {{ minTimeMin }} minutes and lesser or equal with {{ maxTimeMin }} minutes';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
