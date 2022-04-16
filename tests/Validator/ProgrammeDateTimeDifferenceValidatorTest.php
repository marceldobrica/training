<?php

declare(strict_types=1);

namespace App\Tests\Validator;

use App\Entity\Programme;
use App\Validator\ProgrammeDateTimeDifference;
use App\Validator\ProgrammeDateTimeDifferenceValidator;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class ProgrammeDateTimeDifferenceValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): ConstraintValidatorInterface
    {
        return new ProgrammeDateTimeDifferenceValidator('360', '15');
    }

    public function provideProgramme(): array
    {
        $programmeSmallDifference = new Programme();
        $programmeSmallDifference->setStartDate(new \DateTime('+5minutes'));
        $programmeSmallDifference->setEndDate(new \DateTime('+15minutes'));

        $programmeGreatDifference = new Programme();
        $programmeGreatDifference->setStartDate(new \DateTime('+5minutes'));
        $programmeGreatDifference->setEndDate(new \DateTime('+370minutes'));

        $programmeNormal = new Programme();
        $programmeNormal->setStartDate(new \DateTime('+5minutes'));
        $programmeNormal->setEndDate(new \DateTime('+75minutes'));

        return [
            [$programmeSmallDifference, false],
            [$programmeGreatDifference, false],
            [$programmeNormal, true],
        ];
    }

    /**
     * @dataProvider provideProgramme
     */
    public function testDateDifferences(Programme $programme, bool $expectedValid): void
    {
        $this->validator->validate($programme, new ProgrammeDateTimeDifference());
        if ($expectedValid) {
            $this->assertNoViolation();
        } else {
            $this->buildViolation('Difference between programmes end date and start date should be greater or equal 
                                with 15 minutes and lesser or equal with 6 hours')->assertRaised();
        }
    }
}
