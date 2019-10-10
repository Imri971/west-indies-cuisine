<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Steps;
use App\Entity\Recipe;
use App\Form\StepsType;
use App\Entity\Ingredient;
use App\Entity\KitchenTools;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\CollectionType;

class RecipeType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title')
            ->add('image')
            ->add('description')
            ->add('preparation_time')
            ->add('price_range',ChoiceType::class, [
                'choices' => [
                    'Low cost' => 1,
                    'Medium cost' => 2,
                    'High cost' => 3
                ]
            ])
            ->add('difficulty_level',ChoiceType::class, [
                'choices' => [
                    'Beginner' => 1,
                    'Intermediary' => 2,
                    'Experienced' => 3
                ]
            ])
            ->add('tags', EntityType::class, [
                'class' => Tag::class,
                'expanded' => false,
                'multiple' => true
            ] )
            ->add('ingredients', EntityType::class, [
                'class' => Ingredient::class,
                'expanded' => true,
                'multiple' => true
            ] )
            ->add('kitchen_tools', EntityType::class, [
                'class' => KitchenTools::class,
                'choice_label' => 'name',
                'expanded' => true,
                'multiple' => true
            ])
            
            ->add('steps', CollectionType::class, [
                'entry_type' => StepsType::class,
                'allow_add' => true,
                'allow_delete' => true,
                'prototype' =>true,
                'label' => false
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
             'data_class' => Recipe::class,
        ]);
    }
}
