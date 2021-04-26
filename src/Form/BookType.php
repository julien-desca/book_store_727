<?php

namespace App\Form;

use App\Entity\Author;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\MoneyType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class BookType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
        ->add('title', TextType::class, ['label' => 'titre'])
        ->add('description', TextareaType::class, [
            'label' => 'description',
            'attr' => [
                'rows' => 7
            ]
        ])
        ->add('categories', EntityType::class, [
            'label' => 'catégories',
            'class' => Category::class,
            'choice_label' => 'name',
            'multiple' => true,
            'expanded' => true
        ])
        ->add('author', EntityType::class, [
            'label' => 'auteur(e)',
            'class' => Author::class,
            'choice_label' => function($author){
                return $author->getFirstName() . " " . $author->getLastName();
            },
        ])
        ->add('note', IntegerType::class)
        ->add('isbn10', TextType::class)
        ->add('price', MoneyType::class, [
            'label' => 'prix du livre'
        ])
        ->add('save', SubmitType::class, ['label' => 'Enregistrer']);
    }

}