<?php

declare(strict_types=1);

namespace App\Tests\Form\Type;

use App\Entity\Room;
use App\Entity\User;
use App\Form\Type\ProgrammeType;
use App\Repository\UserRepository;
use PHPUnit\Framework\TestCase;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ProgrammeTypeTest extends TestCase
{
    private ProgrammeType $instance;

    protected function setUp(): void
    {
        $this->instance = new ProgrammeType();
    }

    public function testBuildingTheForm(): void
    {
        $formBuilder = $this->createMock(FormBuilderInterface::class);

        $formBuilder
            ->expects($this->exactly(10))
            ->method('add')
            ->withConsecutive(
                ['name', TextType::class],
                ['description', TextType::class],
                [
                    'startDate',
                    DateTimeType::class,
                    [
                        'date_label' => 'Start date',
                        'date_widget' => 'single_text',
                        'time_widget' => 'single_text',
                        'input_format' => 'H:i'
                    ]
                ],
                [
                    'endDate',
                    DateTimeType::class,
                    [
                        'date_label' => 'End date',
                        'date_widget' => 'single_text',
                        'time_widget' => 'single_text',
                        'input_format' => 'H:i'
                    ]
                ],
                ['isOnline', CheckboxType::class, ['required' => false]],
                ['maxParticipants', TextType::class],
                [
                    'trainer',
                    EntityType::class,
                    [
                        'class' => User::class,
                        'query_builder' => function (UserRepository $er) {
                            return $er->createQueryBuilder('u')
                                ->andWhere('u.roles LIKE \'%ROLE_ADMIN%\' OR u.roles LIKE \'%ROLE_TRAINER%\'');
                        },
                        'required' => false
                    ]
                ],
                ['room', EntityType::class, ['class' => Room::class, 'required' => true]],
                ['customers', EntityType::class, ['class' => User::class, 'multiple' => true, 'required' => false]],
                ['save', SubmitType::class, ['label' => 'Save Programme']]
            )
            ->willReturnSelf();
        $this->instance->buildForm($formBuilder, []);
    }
}
