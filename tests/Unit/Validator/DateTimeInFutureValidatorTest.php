<?php

declare(strict_types=1);

namespace App\Tests\Unit\Validator;

use App\Validator\DateTimeInFuture;
use App\Validator\DateTimeInFutureValidator;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class DateTimeInFutureValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): ConstraintValidatorInterface
    {
        return new DateTimeInFutureValidator();
    }

    public function provideDateTime(): array
    {
        return [
            [new \DateTime('now'), false],
            [new \DateTime('-1 day'), false],
            [new \DateTime('+5 seconds'), true],
        ];
    }

    /**
     * @dataProvider provideDateTime
     */
    public function testDatesInFuture(\DateTime $dateTime, bool $expectedValid): void
    {
        $this->validator->validate($dateTime, new DateTimeInFuture());
        if ($expectedValid) {
            $this->assertNoViolation();
        } else {
            $this->buildViolation('Programmes start and end dates should be greater than current moment! ' .
                'You are not allowed to modify past programmes.')->assertRaised();
        }
    }
}
