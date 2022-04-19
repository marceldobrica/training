<?php

namespace App\Form\Type;

use App\Entity\Programme;
use App\Entity\Room;
use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProgrammeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('description', TextType::class)
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
            ->add('isOnline', CheckboxType::class, ['required' => false])
            ->add('maxParticipants', TextType::class)
            ->add('trainer', EntityType::class, [
                'class' => User::class,
                'query_builder' => function (UserRepository $er) {
                    return $er->createQueryBuilder('u')
                        ->andWhere('u.roles LIKE \'%ROLE_ADMIN%\' OR u.roles LIKE \'%ROLE_TRAINER%\'');
                },
                'required' => false])
            ->add('room', EntityType::class, ['class' => Room::class, 'required' => true])
            ->add('customers', EntityType::class, ['class' => User::class, 'multiple' => true, 'required' => false])
            ->add('save', SubmitType::class, ['label' => 'Save Programme']);
    }
}
