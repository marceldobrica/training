<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form\Type;

use App\Entity\Building;
use App\Form\Type\RoomType;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RoomTypeTest extends TestCase
{
    private RoomType $instance;

    protected function setUp(): void
    {
        $this->instance = new RoomType();
    }

    public function testBuildingTheForm(): void
    {
        $formBuilder = $this->createMock(FormBuilderInterface::class);

        $formBuilder
            ->expects($this->exactly(4))
            ->method('add')
            ->withConsecutive(
                ['name', TextType::class],
                ['capacity', TextType::class],
                ['building', EntityType::class, ['class' => Building::class, 'required' => false]],
                ['save', SubmitType::class, ['label' => 'Save Room']]
            )
            ->willReturnSelf();
        $this->instance->buildForm($formBuilder, []);
    }
}
