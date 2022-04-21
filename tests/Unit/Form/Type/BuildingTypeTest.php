<?php

declare(strict_types=1);

namespace App\Tests\Unit\Form\Type;

use App\Form\Type\BuildingType;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class BuildingTypeTest extends TestCase
{
    private BuildingType $instance;

    public function setUp(): void
    {
        $this->instance = new BuildingType();
    }

    public function testBuildingTheForm(): void
    {
        $formBuilder = $this->createMock(FormBuilderInterface::class);

        $formBuilder
            ->expects($this->exactly(4))
            ->method('add')
            ->withConsecutive(
                ['address', TextType::class],
                [
                    'startTime',
                    DateTimeType::class,
                    [
                        'date_label' => 'Current date',
                        'date_widget' => 'single_text',
                        'time_label' => 'Start time',
                        'time_widget' => 'single_text',
                        'input_format' => 'H:i'
                    ]
                ],
                [
                    'endTime',
                    DateTimeType::class,
                    [
                        'date_label' => 'Current date',
                        'date_widget' => 'single_text',
                        'time_label' => 'End time',
                        'time_widget' => 'single_text',
                        'input_format' => 'H:i'
                    ]
                ],
                ['save', SubmitType::class, ['label' => 'Save building']]
            )
            ->willReturnSelf();
        $this->instance->buildForm($formBuilder, []);
    }
}
