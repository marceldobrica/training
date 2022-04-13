<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\ResponseEvent;

class ApiKeyVersionSubscriber implements EventSubscriberInterface
{
    private string $apiVersionKey;

    public function __construct(string $apiVersionKey)
    {
        $this->apiVersionKey = $apiVersionKey;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            ResponseEvent::class => 'addApiVersionKey'
        ];
    }

    public function addApiVersionKey(ResponseEvent $event): void
    {
        $route = $event->getRequest()->attributes->get('_route');
        if (null !== $route && \strpos($route, 'api_') !== false) {
            $event->getResponse()->headers->set('X-API-VERSION', $this->apiVersionKey);
        }
    }
}
