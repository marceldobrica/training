<?php

declare(strict_types=1);

namespace App\EventSubscriber;

use App\Entity\User;
use App\Repository\ProgrammeRepository;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Gedmo\SoftDeleteable\SoftDeleteableListener;
use Doctrine\Bundle\DoctrineBundle\EventSubscriber\EventSubscriberInterface;

class SoftDeleteUserSubscriber implements EventSubscriberInterface
{
    private ProgrammeRepository $programmeRepository;

    public function __construct(ProgrammeRepository $programmeRepository)
    {
        $this->programmeRepository = $programmeRepository;
    }

    public function getSubscribedEvents(): array
    {
        return [SoftDeleteableListener::POST_SOFT_DELETE];
    }

    public function postSoftDelete(LifecycleEventArgs $eventArgs): void
    {
        $user = $eventArgs->getObject();
        if (!$user instanceof User || !in_array('ROLE_TRAINER', $user->getRoles(), true)) {
            return;
        }

        $this->programmeRepository->setTrainerNull($user->getId());
    }
}
