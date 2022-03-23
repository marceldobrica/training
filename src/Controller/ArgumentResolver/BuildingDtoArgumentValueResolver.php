<?php

declare(strict_types=1);

namespace App\Controller\ArgumentResolver;

use App\Controller\Dto\BuildingDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class BuildingDtoArgumentValueResolver implements ArgumentValueResolverInterface
{
    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === BuildingDto::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        $data = $request->getContent();
        $decodedData = json_decode($data, true);
        $dto = new BuildingDto();

        $dto->startTime = \DateTime::createFromFormat('d.m.Y H:i', $decodedData['startTime']);
        $dto->endTime = \DateTime::createFromFormat('d.m.Y H:i', $decodedData['endTime']);

        yield $dto;
    }
}
