<?php

declare(strict_types=1);

namespace App\Tests\Validator;

use App\Validator\Cnp;
use App\Validator\CnpValidator;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class CnpValidatorTest extends ConstraintValidatorTestCase
{
    public function testCnpLength(): void
    {
        $cnp = '1234';
        $this->validator->validate($cnp, new Cnp());

        $this->buildViolation('This is not a valid CNP')->assertRaised();
    }

    protected function createValidator(): ConstraintValidatorInterface
    {
        return new CnpValidator();
    }

    public function provideCnp(): array
    {
        return [
            [ '0', false],
            [ '101', false],
            [ '1660713034972', true],
        ];
    }

    /**
     * @dataProvider provideCnp
     */
    public function testCnp(string $cnp, bool $expectedValid): void
    {
        $this->validator->validate($cnp, new Cnp());
        if ($expectedValid) {
            $this->assertNoViolation();
        } else {
            $this->buildViolation('This is not a valid CNP')->assertRaised();
        }
    }

    public function testValidCnp(): void
    {
        $cnp = '1660713034972';
        $this->validator->validate($cnp, new Cnp());
        $this->assertNoViolation();
    }
}
