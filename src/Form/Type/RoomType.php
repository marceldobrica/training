<?php

namespace App\Form\Type;

use App\Entity\Building;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class RoomType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class)
            ->add('capacity', TextType::class)
            ->add('building', EntityType::class, ['class' => Building::class, 'required' => false])
            ->add('save', SubmitType::class, ['label' => 'Save Room'])
        ;
    }
}
