<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form\Type;

use App\Form\Type\ResetPasswordType;
use App\Validator as MyAsserts;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ResetPasswordTypeTest extends TestCase
{
    private ResetPasswordType $instance;

    protected function setUp(): void
    {
        $this->instance = new ResetPasswordType();
    }

    public function testBuildingTheForm(): void
    {
        $formBuilder = $this->createMock(FormBuilderInterface::class);

        $formBuilder
            ->expects($this->exactly(2))
            ->method('add')
            ->withConsecutive(
                [
                    'password',
                    RepeatedType::class,
                    [
                        'type' => PasswordType::class,
                        'invalid_message' => 'The password fields must match.',
                        'options' => ['attr' => ['class' => 'password-field']],
                        'required' => true,
                        'first_options'  => ['label' => 'Password'],
                        'second_options' => ['label' => 'Repeat Password'],
                        'constraints' => [new MyAsserts\Password()],
                        'error_bubbling' => true,
                    ]
                ],
                [
                    'save',
                    SubmitType::class,
                    ['label' => 'Save new password'],
                ]
            )
            ->willReturnSelf();
        $this->instance->buildForm($formBuilder, []);
    }
}
