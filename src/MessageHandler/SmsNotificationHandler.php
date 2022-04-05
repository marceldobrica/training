<?php

declare(strict_types=1);

namespace App\MessageHandler;

use App\HttpClient\HttpClientSmsUser;
use App\Message\SmsNotification;
use Symfony\Component\Messenger\Handler\MessageHandlerInterface;

class SmsNotificationHandler implements MessageHandlerInterface
{
    private HttpClientSmsUser $httpClientSmsUser;

    public function __construct(HttpClientSmsUser $httpClientSmsUser)
    {
        $this->httpClientSmsUser = $httpClientSmsUser;
    }

    public function __invoke(SmsNotification $message)
    {
        $this->httpClientSmsUser->sendSms($message->getPhone(), $message->getContent());
    }
}
