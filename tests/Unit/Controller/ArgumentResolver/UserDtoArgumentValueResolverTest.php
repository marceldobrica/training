<?php

declare(strict_types=1);

namespace App\Tests\Unit\Controller\ArgumentResolver;

use App\Controller\ArgumentResolver\UserDtoArgumentValueResolver;
use App\Controller\Dto\UserDto;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\SerializerInterface;

final class UserDtoArgumentValueResolverTest extends TestCase
{
    private MockObject $serializer;

    private UserDtoArgumentValueResolver $userDtoArgumentValueResolver;

    public function setUp(): void
    {
        parent::setUp();

        $this->serializer = $this->createMock(SerializerInterface::class);

        $this->userDtoArgumentValueResolver = new UserDtoArgumentValueResolver($this->serializer);
    }

    public function testSupportInvalidValue(): void
    {
        $requestMock = $this->createMock(Request::class);
        $argumentMetadata = $this->createMock(ArgumentMetadata::class);
        $argumentMetadata->expects($this->once())->method('getType')->willReturn('test');

        $this->assertFalse($this->userDtoArgumentValueResolver->supports($requestMock, $argumentMetadata));
    }

    public function testSupportValidValue(): void
    {
        $requestMock = $this->createMock(Request::class);
        $argumentMetadata = $this->createMock(ArgumentMetadata::class);
        $argumentMetadata->method('getType')->willReturn(UserDto::class);

        $this->assertTrue($this->userDtoArgumentValueResolver->supports($requestMock, $argumentMetadata));
    }
}
