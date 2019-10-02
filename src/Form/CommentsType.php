<?php

namespace App\Form;

use App\Entity\Comments;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CommentsType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            //->add('author')
            ->add('content')
            //->add('picture')
            //->add('createdAt')
            //->add('recipe')
            //->add('submit',SubmitType::class);
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Comments::class,
        ]);
    }
}
