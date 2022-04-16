<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form\Type;

use App\Form\Type\RequestNewPasswordType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class RequestNewPasswordTypeTest extends TestCase
{
    private RequestNewPasswordType $instance;

    protected function setUp(): void
    {
        $this->instance = new RequestNewPasswordType();
    }

    public function testBuildingTheForm(): void
    {
        $formBuilder = $this->createMock(FormBuilderInterface::class);
        $formBuilder
            ->expects($this->exactly(2))
            ->method('add')
            ->withConsecutive(
                [
                    'email',
                    EmailType::class,
                ],
                [
                    'save',
                    SubmitType::class,
                    ['label' => 'Request new password'],
                ]
            )
            ->willReturnSelf();
        $this->instance->buildForm($formBuilder, []);
    }
}
