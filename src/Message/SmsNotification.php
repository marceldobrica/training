<?php

declare(strict_types=1);

namespace App\Message;

class SmsNotification
{
    private string $content;

    private string $phone;

    public function __construct(string $content, string $phone)
    {
        $this->content = $content;
        $this->phone = $phone;
    }

    public function getContent(): string
    {
        return $this->content;
    }

    public function getPhone(): string
    {
        return $this->phone;
    }
}
