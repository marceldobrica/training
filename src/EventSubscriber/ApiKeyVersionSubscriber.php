<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\RequestEvent;

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
            RequestEvent::class => ['addApiVersionKey', 257]
        ];
    }

    public function addApiVersionKey(RequestEvent $event): void
    {
        $event->getRequest()->headers->set('X-API-VERSION', $this->apiVersionKey);
    }
}
