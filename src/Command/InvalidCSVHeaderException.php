<?php

declare(strict_types=1);

namespace App\Command;

class InvalidCSVHeaderException extends \Exception
{
    public function errorMessage(): string
    {
        return  'Received header from csv file is not in correct format.';
    }
}
