<?php

declare(strict_types=1);

namespace App\Tests\Controller\ArgumentResolver;

use App\Controller\ArgumentResolver\UserDtoArgumentValueResolver;
use App\Controller\Dto\UserDto;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\SerializerInterface;

class UserDtoArgumentValueResolverTest extends TestCase
{
    private UserDtoArgumentValueResolver $userDtoArgumentValueResolver;

    private SerializerInterface $serializer;

    public function setUp(): void
    {
        //TODO ... ask more about this solution... it is not a mock but in a mock i tell what to return...

//        $userDto = new UserDto();
//        $userDto->firstName = 'Fabien';
//        $userDto->lastName = 'Potencier';
//        $userDto->email = 'some@example.com';
//        $userDto->cnp = '1660713034972';
//        $userDto->password = 'alabala';
//        $userDto->confirmedPassword = 'alabala';
//
//        $this->serializer = $this->createMock(SerializerInterface::class);
        //$this->serializer->method('resolve')->willReturn($this->generator([$userDto]));

        // tests without mock
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer()];
        $this->serializer = new Serializer($normalizers, $encoders);

        $this->userDtoArgumentValueResolver = new UserDtoArgumentValueResolver($this->serializer);
    }

    private function generator(array $yieldValues): \Generator
    {
        foreach ($yieldValues as $value) {
            yield $value;
        }
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
