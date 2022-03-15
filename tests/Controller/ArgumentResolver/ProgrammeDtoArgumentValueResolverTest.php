<?php

declare(strict_types=1);

namespace App\Tests\Controller\ArgumentResolver;

use App\Controller\ArgumentResolver\ProgrammeDtoArgumentValueResolver;
use App\Controller\Dto\ProgrammeDto;
use App\Entity\Room;
use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use PHPUnit\Framework\MockObject\MockClass;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class ProgrammeDtoArgumentValueResolverTest extends TestCase
{
    private ProgrammeDtoArgumentValueResolver $programmeDtoArgumentValueResolver;

    /**
     * @var EntityManagerInterface|MockClass
     */
    private $entityManager;

    public function setUp(): void
    {
        $this->entityManager = $this->createMock(EntityManagerInterface::class);
        $this->programmeDtoArgumentValueResolver = new ProgrammeDtoArgumentValueResolver($this->entityManager);
    }

    public function testProgrammeDtoArgumentValueResolver(): void
    {
        $request = Request::create('/test');
        $argumentMetaData = new ArgumentMetadata('test', ProgrammeDto::class, true, false, new ProgrammeDto());
        $result = $this->programmeDtoArgumentValueResolver->supports($request, $argumentMetaData);

        self::assertNotFalse($result);
    }
}
