<?php

declare(strict_types=1);

namespace App\Command;

class NotAbleToAssignRoomException extends \Exception
{
    public function errorMessage(): string
    {
        return  'Not able to assign a room to programme';
    }
}
