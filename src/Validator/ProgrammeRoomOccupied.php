<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ProgrammeRoomOccupied extends Constraint
{
    public string $message = 'The room is already occupied for settled period.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
