<?php

namespace App\Controller\Api;

use App\Controller\Dto\RoomDto;
use App\Controller\ReturnValidationErrorsTrait;
use App\Entity\Room;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerAwareInterface;
use Psr\Log\LoggerAwareTrait;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

use function App\Controller\count;

/**
 * @Route (path="/api/rooms")
 */
class ApiRoomsController implements LoggerAwareInterface
{
    use LoggerAwareTrait;

    use ReturnValidationErrorsTrait;

    private EntityManagerInterface $entityManager;

    private ValidatorInterface $validator;

    public function __construct(EntityManagerInterface $entityManager, ValidatorInterface $validator)
    {
        $this->entityManager = $entityManager;
        $this->validator = $validator;
    }

    /**
     * @Route(methods={"POST"})
     */
    public function createRoomAction(RoomDto $roomDto): Response
    {
        $errors = $this->validator->validate($roomDto);
        if (count($errors) > 0) {
            return $this->returnValidationErrors($errors);
        }

        $this->logger->info('A roomDto was validated');

        $room = Room::createFromDto($roomDto);
        $errors = $this->validator->validate($room);

        if (count($errors) > 0) {
            return $this->returnValidationErrors($errors);
        }

        $this->entityManager->persist($room);
        $this->entityManager->flush();
        $this->entityManager->refresh($room);

        $this->logger->info('A room was registered and saved in DB');

        $savedRoomDto = RoomDto::createFromRoom($room);

        return new JsonResponse($savedRoomDto, Response::HTTP_CREATED);
    }
}
