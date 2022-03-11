<?php

declare(strict_types=1);

namespace App\Tests\Feeder;

use App\Animal\Duck;
use App\Feeder\AnimalFeeder;
use App\Tests\Stub\Animal\Dog;
use PHPUnit\Framework\MockObject\MockClass;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class AnimalFeederTest extends TestCase
{
    private AnimalFeeder $feeder;

    /**
     * @var ValidatorInterface|MockClass
     */
    private $validator;

    public function provideFeed(): array
    {
        return [
            [ 0, false, ['test element'] ],
            [ 101, false, ['test element'] ],
            [ 1, true, [] ],
            [ 99, true, [] ],
        ];
    }

    protected function setUp(): void
    {
        parent::setUp();

        $this->validator = $this->createMock(ValidatorInterface::class);

        $this->feeder = new AnimalFeeder($this->validator);
    }

    public function testFeedWhenAnimalIsDead(): void
    {
        $duck = new Duck();
        $duck->setBelly(0);

        $this->validator->expects($this->any())
            ->method('validate')
            ->willReturn(['test element']);

        $result = $this->feeder->feedBird($duck);

        self::assertFalse($result);
    }

    public function testFeedWhenAnimalIsFat(): void
    {
        $duck = new Duck();
        $duck->setBelly(101); // very fat

        $this->validator->expects($this->any())
            ->method('validate')
            ->willReturn(['test element']);

        $result = $this->feeder->feedBird($duck);

        self::assertFalse($result);
        self::assertNotTrue($result);
    }

    public function testFeed(): void
    {
        $duck = new Duck();
        $duck->setBelly(1);

        $this->validator->expects($this->any())
            ->method('validate')
            ->willReturn([]);

        $result = $this->feeder->feedBird($duck);

        self::assertTrue($result);
        self::assertSame($result, true);
    }

    /**
     * @dataProvider provideFeed
     */
    public function testAllFeed(int $belly, bool $expected, array $errors): void
    {
        $duck = new Duck();
        $duck->setBelly($belly);

        $this->validator->expects($this->any())
            ->method('validate')
            ->willReturn($errors);

        $result = $this->feeder->feedBird($duck);

        self::assertSame($result, $expected);
    }

    public function testFeedBirdWithDog(): void
    {
        self::expectException(\UnexpectedValueException::class);
        self::expectExceptionMessage('Vrem egalitate, dar nu pentru catei');

        $duck = new Dog();
        $duck->setBelly(1);

        $this->validator->expects($this->any())
            ->method('validate')
            ->willReturn([]);

        $this->feeder->feedBird($duck);
    }
}
