<?php

namespace App\Validator;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class Password extends Constraint
{
    public $message = "This is not a valid password. A password should have:" . PHP_EOL .
                        "- at least 8 alphanumeric characters" . PHP_EOL .
                        "- at least one uppercase letter" . PHP_EOL .
                        "- at least one special character" . PHP_EOL .
                        "- should not include spaces, tabs, whitespaces";
}
