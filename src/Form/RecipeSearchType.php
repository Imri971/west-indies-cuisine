<?php

namespace App\Form;

use App\Entity\Tag;
use App\Entity\Ingredient;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RecipeSearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
           // ->add('title')
            // ->add('tags', EntityType::class, [
            //     'class' => Tag::class,
            //     'choice_label' => 'name'
            // ])
            ->add('preparationTime', ChoiceType::class, [
                'choices' => [
                    '<30min' => 30,
                    '<1h' => 60
                ]
            ])
            ->add('priceRange',ChoiceType::class, [
                'choices' => [
                    'Low cost' => 1,
                    'Medium cost' => 2,
                    'High cost' => 3
                ]
            ])
            ->add('difficultyLevel',ChoiceType::class, [
                'choices' => [
                    'Beginner' => 1,
                    'Intermediary' => 2,
                    'Experienced' => 3
                ]
            ])
            // ->add('ingredients', EntityType::class, [
            //     'class' => Ingredient::class,
            //     'choice_label' => 'name'
            // ])
            ->add('submit', SubmitType::class)
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
