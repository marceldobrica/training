<?php

declare(strict_types=1);

namespace App\Tests\Validator;

use App\Validator\Password;
use App\Validator\PasswordValidator;
use Symfony\Component\Validator\ConstraintValidatorInterface;
use Symfony\Component\Validator\Test\ConstraintValidatorTestCase;

class PasswordValidatorTest extends ConstraintValidatorTestCase
{
    protected function createValidator(): ConstraintValidatorInterface
    {
        return new PasswordValidator();
    }

    public function providePassword(): array
    {
        return [
            ["166@0713", false],
            ["", false],
            ["as \n\ras \tas", false],
            ["as@asAasdxyz", true]
        ];
    }

    /**
     * @dataProvider providePassword
     */
    public function testPassword(string $password, bool $expected): void
    {
        $this->validator->validate($password, new Password());
        if ($expected) {
            $this->assertNoViolation();
        } else {
            $this->buildViolation("This is not a valid password. A password should have:" . PHP_EOL .
                "- at least 8 alphanumeric characters" . PHP_EOL .
                "- at least one uppercase letter" . PHP_EOL .
                "- at least one special character" . PHP_EOL .
                "- should not include spaces, tabs, whitespaces")->assertRaised();
        }
    }
}
