<?php

namespace App\Form\Type;

use App\Entity\Building;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class BuildingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('address')
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

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Building::class,
        ]);
    }
}
