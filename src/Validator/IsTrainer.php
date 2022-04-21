<?php

declare(strict_types=1);

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class IsTrainer extends Constraint
{
    public $message = "The user must be a trainer!";
}
