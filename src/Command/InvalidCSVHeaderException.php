<?php

declare(strict_types=1);

namespace App\Command;

class InvalidCSVHeaderException extends \Exception
{
    protected $message = 'Received header from csv file is not in correct format.';
}
