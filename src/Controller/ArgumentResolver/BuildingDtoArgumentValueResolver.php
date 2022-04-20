<?php

declare(strict_types=1);

namespace App\Controller\ArgumentResolver;

use App\Controller\Dto\BuildingDto;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Controller\ArgumentValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;

class BuildingDtoArgumentValueResolver implements ArgumentValueResolverInterface
{
    private SerializerInterface $serializer;

    public function __construct(SerializerInterface $serializer)
    {
        $this->serializer = $serializer;
    }

    public function supports(Request $request, ArgumentMetadata $argument): bool
    {
        return $argument->getType() === BuildingDto::class;
    }

    public function resolve(Request $request, ArgumentMetadata $argument): \Generator
    {
        $dto = $this->serializer->deserialize($request->getContent(), BuildingDto::class, 'json');

        yield $dto;
    }
}
