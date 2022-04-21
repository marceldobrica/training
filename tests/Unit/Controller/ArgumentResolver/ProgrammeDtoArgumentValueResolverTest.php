<?php

declare(strict_types=1);

namespace App\Tests\Controller\ArgumentResolver;

use App\Controller\ArgumentResolver\ProgrammeDtoArgumentValueResolver;
use App\Controller\Dto\ProgrammeDto;
use App\Repository\ProgrammeRepository;
use App\Repository\RoomRepository;
use PHPUnit\Framework\MockObject\MockClass;
use Doctrine\ORM\EntityManagerInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Security\Core\Security;

class ProgrammeDtoArgumentValueResolverTest extends TestCase
{
    private ProgrammeDtoArgumentValueResolver $programmeDtoArgumentValueResolver;

    /**
     * @var EntityManagerInterface|MockClass
     */
    private $security;

    /**
     * @var EntityManagerInterface|MockClass
     */
    private $roomRepository;

    /**
     * @var EntityManagerInterface|MockClass
     */
    private $programmeRepository;

    public function setUp(): void
    {
        $this->security = $this->createMock(Security::class);
        $this->roomRepository = $this->createMock(RoomRepository::class);
        $this->programmeRepository = $this->createMock(ProgrammeRepository::class);
        $this->programmeDtoArgumentValueResolver = new ProgrammeDtoArgumentValueResolver(
            $this->security,
            $this->roomRepository,
            $this->programmeRepository
        );
    }

    public function testProgrammeDtoArgumentValueResolver(): void
    {
        $request = Request::create('/test');
        $argumentMetaData = new ArgumentMetadata('test', ProgrammeDto::class, true, false, new ProgrammeDto());
        $result = $this->programmeDtoArgumentValueResolver->supports($request, $argumentMetaData);

        self::assertNotFalse($result);
    }
}
