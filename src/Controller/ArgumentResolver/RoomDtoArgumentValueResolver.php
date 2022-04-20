<?php

declare(strict_types=1);

namespace App\Controller\ArgumentResolver;

use App\Controller\Dto\RoomDto;
use App\Repository\BuildingRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class RoomDtoArgumentValueResolver implements ArgumentValueResolverInterface
{
    private BuildingRepository $buildingRepository;

    /**
     * @param BuildingRepository $buildingRepository
     */
    public function __construct(BuildingRepository $buildingRepository)
    {
        $this->buildingRepository = $buildingRepository;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === RoomDto::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        $data = $request->getContent();
        $decodedData = \json_decode($data, true);

        $dto = new RoomDto();
        $dto->name = $decodedData['name'];
        $dto->capacity = intval($decodedData['capacity']);

        if (isset($decodedData['buildingId'])) {
            $dto->building = $this->buildingRepository->findOneById($decodedData['buildingId']);
        }

        yield $dto;
    }
}
