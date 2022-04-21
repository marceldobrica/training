<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form\Type;

use App\Form\Type\DeleteCancelType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class DeleteCancelTypeTest extends TestCase
{
    private DeleteCancelType $instance;

    public function setUp(): void
    {
        $this->instance = new DeleteCancelType();
    }

    public function testBuildingTheForm(): void
    {
        $formBuilder = $this->createMock(FormBuilderInterface::class);

        $formBuilder
            ->expects($this->exactly(2))
            ->method('add')
            ->withConsecutive(
                ['delete', SubmitType::class, ['label' => 'DELETE']],
                ['cancel', SubmitType::class, ['label' => 'Cancel']]
            )
            ->willReturnSelf();
        $this->instance->buildForm($formBuilder, []);
    }
}
