<?php

declare(strict_types=1);

namespace App\Tests\Validator;

use App\Entity\Programme;
use App\Validator\ProgrammeDateTimeNotInPast;
use App\Validator\ProgrammeDateTimeNotInPastValidator;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class ProgrammeDateTimeNotInPastValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): ConstraintValidatorInterface
    {
        return new ProgrammeDateTimeNotInPastValidator();
    }

    public function provideDateTime(): array
    {
        return [
            [new \DateTime('now'), false],
            [new \DateTime('-1 day'), false],
            [new \DateTime('+1 second'), true],
        ];
    }

    /**
     * @dataProvider provideDateTime
     */
    public function testDatesNotInPast(\DateTime $dateTime, bool $expectedValid): void
    {
        $this->validator->validate($dateTime, new ProgrammeDateTimeNotInPast());
        if ($expectedValid) {
            $this->assertNoViolation();
        } else {
            $this->buildViolation('Programmes start and end dates should be greater than current moment! 
                    You are not allowed to modify past programmes.')->assertRaised();
        }
    }
}
