<?php

declare(strict_types=1);

namespace App\Tests\Controller\ArgumentResolver;

use App\Controller\ArgumentResolver\UserDtoArgumentValueResolver;
use App\Controller\Dto\UserDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

class UserDtoArgumentValueResolverTest extends TestCase
{
    private UserDtoArgumentValueResolver $userDtoArgumentValueResolver;

    public function setUp(): void
    {
        parent::setUp();
        $this->userDtoArgumentValueResolver = new UserDtoArgumentValueResolver();
    }

    public function testUserDtoArgumentValueResolver(): void
    {
        $request = Request::create('/test');
        $argumentMetaData = new ArgumentMetadata('test', UserDto::class, true, false, new UserDto());
        $result = $this->userDtoArgumentValueResolver->supports($request, $argumentMetaData);

        self::assertNotFalse($result);
    }

    public function testResolveArgument()
    {
        $request = Request::create(
            '/test',
            'POST',
            [],
            [],
            [],
            [],
            json_encode([
                'firstName' => 'Fabien',
                'lastName' => 'Potencier',
                'email' => 'some@example.com',
                'cnp' => '1660713034972',
                'password' => 'alabala',
                'confirmedPassword' => 'alabala'
            ])
        );

        $argumentMetadata = new ArgumentMetadata('test', UserDto::class, true, false, new UserDto());
        $dto = $this->userDtoArgumentValueResolver->resolve($request, $argumentMetadata)->current();

        $userDto = new UserDto();
        $userDto->firstName = 'Fabien';
        $userDto->lastName = 'Potencier';
        $userDto->email = 'some@example.com';
        $userDto->cnp = '1660713034972';
        $userDto->password = 'alabala';
        $userDto->confirmedPassword = 'alabala';

        self::assertIsIterable($this->userDtoArgumentValueResolver->resolve($request, $argumentMetadata));
        self::assertEquals($userDto, $dto);
    }
}
