<?php

declare(strict_types=1);

namespace App\Form\Type;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserModifyType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('firstName', TextType::class)
            ->add('lastName', TextType::class)
            ->add('email', EmailType::class)
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'expanded' => true,
                'choices' => [
                    'ROLE_USER' => 'ROLE_USER',
                    'ROLE_TRAINER' => 'ROLE_TRAINER',
                    'ROLE_ADMIN' => 'ROLE_ADMIN',
                ],
            ])
            ->add('phone', TextType::class)
            ->add('save', SubmitType::class, ['label' => 'Save User'])
        ;

        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function (array $rolesArray) {
                    return array_values($rolesArray)[0];
                },
                function (string $rolesString) {
                    return [0 => $rolesString];
                }
            ));
    }
}
