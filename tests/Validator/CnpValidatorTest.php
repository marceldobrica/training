<?php

declare(strict_types=1);

namespace App\Tests\Validator;

use App\Validator\Cnp;
use App\Validator\CnpValidator;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class CnpValidatorTest extends ConstraintValidatorTestCase
{
    public function testCnpLength(): void
    {
        $cnp = '1234';
        $result = $this->validator->validate($cnp, new Cnp());

        $this->buildViolation('This is not a valid CNP')->assertRaised();
        //$this->assertNoViolation();
    }

    protected function createValidator()
    {
        return new CnpValidator();
    }
}
