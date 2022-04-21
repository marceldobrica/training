<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Event\UserCreatedEvent;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class UserCreatedSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $analyticsLogger)
    {
        $this->logger = $analyticsLogger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
           UserCreatedEvent::NAME => 'onUserCreated',
        ];
    }

    public function onUserCreated(UserCreatedEvent $event)
    {
        $user = $event->getUser();
        $this->logger->info('User created', [
            'userid' => $user->getId(),
            'username' => $user->email,
            'roles' => $user->getRoles()
        ]);
    }
}
