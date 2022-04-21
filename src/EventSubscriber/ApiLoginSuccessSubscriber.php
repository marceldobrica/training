<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\User;
use Psr\Log\LoggerInterface;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Security\Http\Event\LoginSuccessEvent;

class ApiLoginSuccessSubscriber implements EventSubscriberInterface
{
    private LoggerInterface $logger;

    public function __construct(LoggerInterface $analyticsLogger)
    {
        $this->logger = $analyticsLogger;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            LoginSuccessEvent::class => 'addLoginSuccessLog',
        ];
    }

    public function addLoginSuccessLog(LoginSuccessEvent $event)
    {
        /** @var User $user */
        $user = $event->getUser();
        $route = $event->getRequest()->attributes->get('_route');
        if (null !== $route && \strpos($route, 'api_') !== false) {
            $this->logger->info(
                'Success login',
                ['userid' => $user->getId(), 'username' => $user->email, 'route' => $route]
            );
        }
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            $this->logger->info('Admin success login', ['userid' => $user->getId(), 'username' => $user->email]);
        }
    }
}
