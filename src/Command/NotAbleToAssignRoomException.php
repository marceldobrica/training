<?php

declare(strict_types=1);

namespace App\Command;

class NotAbleToAssignRoomException extends \Exception
{
    protected $message = 'Not able to assign a room to programme';
}
