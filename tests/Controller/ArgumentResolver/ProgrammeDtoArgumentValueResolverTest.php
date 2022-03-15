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
//        $this->entityManager->method('getRepositiory')
//        $this->entityManager->expects($this->any())
//            ->method('getRepository', Room::class)
//            ->willReturn(new Room());
        $this->programmeDtoArgumentValueResolver = new ProgrammeDtoArgumentValueResolver($this->entityManager);
    }

    public function testProgrammeDtoArgumentValueResolver(): void
    {
        $request = Request::create('/test');
        $argumentMetaData = new ArgumentMetadata('test', ProgrammeDto::class, true, false, new ProgrammeDto());
        $result = $this->programmeDtoArgumentValueResolver->supports($request, $argumentMetaData);

        self::assertNotFalse($result);
    }

//    public function testResolveArgument()
//    {
//        $request = Request::create(
//            '/test',
//            'POST',
//            [],
//            [],
//            [],
//            [],
//            json_encode([
//                'name' => 'Name',
//                'description' => 'description',
//                'startDate' => '15.05.2022 10:00',
//                'endDate' => '15.05.2022 11:00',
//                'trainer' => '1',
//                'room' => '1',
//                "isOnline" => '1'
//            ])
//        );
//
//        $argumentMetadata = new ArgumentMetadata('test', ProgrammeDto::class, true, false, new ProgrammeDto());
//        $dto = $this->programmeDtoArgumentValueResolver->resolve($request, $argumentMetadata)->current();
//
//        $programeeDto = new ProgrammeDto();
//        $programeeDto->name = 'Name';
//        $programeeDto->description = 'description';
//        $programeeDto->startDate = new \DateTime('15.05.2022 10:00');
//        $programeeDto->endDate = new \DateTime('15.05.2022 11:00');
//        $roomRepository = $this->entityManager->getRepository(Room::class);
//        $room = $roomRepository->find(1);
//        $programeeDto->room = $room;
//        $userRepository = $this->entityManager->getRepository(User::class);
//        $trainer = $userRepository->find(1);
//        $programeeDto->trainer = $trainer;
//        $programeeDto->isOnline = true;
//        $programeeDto->customers = new ArrayCollection(); //customers are
//
//        self::assertIsIterable($this->programmeDtoArgumentValueResolver->resolve($request, $argumentMetadata));
//        //self::assertEquals($programmeDto, $dto);
//    }
}
