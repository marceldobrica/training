<?php

namespace App\Form\Type;

use App\Entity\Programme;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgrammeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name')
            ->add('description')
            ->add('startDate', DateTimeType::class, [
                'date_label' => 'Start date',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'input_format' => 'H:i'
            ])
            ->add('endDate', DateTimeType::class, [
                'date_label' => 'End date',
                'date_widget' => 'single_text',
                'time_widget' => 'single_text',
                'input_format' => 'H:i'
            ])
            ->add('isOnline')
            ->add('maxParticipants')
            ->add('trainer')
            ->add('room')
            ->add('customers')
            ->add('save', SubmitType::class, ['label' => 'Save Programme']);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Programme::class,
        ]);
    }
}
