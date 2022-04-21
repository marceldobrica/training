<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ProgrammeRoomCapacities extends Constraint
{
    public string $message = 'The room should have a capacity greater then max number of programme participants.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
