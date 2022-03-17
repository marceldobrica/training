<?php

declare(strict_types=1);

namespace App\SaveEntities;

use App\Entity\Programme;
use Doctrine\ORM\EntityManagerInterface;

class SaveProgramme
{
    private EntityManagerInterface $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    public function saveProgramme(array $programmeArray): void
    {
        $programme = new Programme();
        $programme->name = $programmeArray['name'];
        $programme->description = $programmeArray['description'];
        $programme->setStartDate(\DateTime::createFromFormat('d.m.Y H:i', $programmeArray['startDate']));
        $programme->setEndDate(\DateTime::createFromFormat('d.m.Y H:i', $programmeArray['startDate']));
        $programme->isOnline = $programmeArray['isOnline'];

        $this->entityManager->persist($programme);
        $this->entityManager->flush();
    }
}
