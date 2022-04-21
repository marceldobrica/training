<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginFailureEvent;

class LoginFailSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $analyticsLogger)
    {
        $this->logger = $analyticsLogger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginFailureEvent::class => 'addLoginFailureLog',
        ];
    }

    public function addLoginFailureLog(LoginFailureEvent $event)
    {
        /** @var User $user */
        $user = $event->getPassport()->getUser();
        $this->logger->error('Login Failed', [
            'firewallname' => $event->getFirewallName(),
            'message' => $event->getException()->getMessage(),
            'userid' => $user->getId(),
            'username' => $user->email,
        ]);
    }
}
