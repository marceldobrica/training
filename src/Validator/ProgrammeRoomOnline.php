<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ProgrammeRoomOnline extends Constraint
{
    public string $message = 'The room and programme should be both online or not.';

    public function getTargets(): string
    {
        return self::CLASS_CONSTRAINT;
    }
}
