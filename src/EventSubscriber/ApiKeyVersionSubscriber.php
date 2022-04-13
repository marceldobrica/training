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
        if (\strpos($event->getResponse()->getContent(), '<!DOCTYPE html') === false) {
            $event->getResponse()->headers->set('X-API-VERSION', $this->apiVersionKey);
        }
    }
}
