<?php

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName')
            ->add('lastName')
            ->add('email')
            ->add('password')
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => false,
                'choices' => [
                    'ROLE_USER' => 'ROLE_USER',
                    'ROLE_TRAINER' => 'ROLE_TRAINER',
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                    ],
                ])
            ->add('cnp')
            ->add('phone')
            ->add('programmes')
            ->add('save', SubmitType::class, ['label' => 'Save User'])
        ;

        // Data transformer
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function (array $rolesArray) {
                    // transform the array to a string
                    return array_values($rolesArray)[0];
                },
                function (string $rolesString) {
                    // transform the string back to an array
                    return [$rolesString];
                }
            ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
