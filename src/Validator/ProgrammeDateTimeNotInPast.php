<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class ProgrammeDateTimeNotInPast extends Constraint
{
    public string $message = "Programmes start and end dates should be greater than current moment! 
                    You are not allowed to modify past programmes.";
}
