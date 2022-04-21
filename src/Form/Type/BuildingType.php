<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class BuildingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address', TextType::class)
            ->add('startTime', DateTimeType::class, [
                'date_label' => 'Current date',
                'date_widget' => 'single_text',
                'time_label' => 'Start time',
                'time_widget' => 'single_text',
                'input_format' => 'H:i'
            ])
            ->add('endTime', DateTimeType::class, [
                'date_label' => 'Current date',
                'date_widget' => 'single_text',
                'time_label' => 'End time',
                'time_widget' => 'single_text',
                'input_format' => 'H:i'
            ])
            ->add('save', SubmitType::class, ['label' => 'Save building']);
    }
}
