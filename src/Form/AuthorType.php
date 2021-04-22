<?php

namespace App\Form;

use DateTime;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\BirthdayType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class AuthorType extends AbstractType{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $today = new DateTime();
        $builder
            ->add('firstName', TextType::class, [
                'label' => 'Prénom',
                'required' => false
            ])
            ->add('lastName', TextType::class, ['label' => 'Nom'])
            ->add('birthDate', BirthdayType::class, [
                'label' => 'Date de naissance',
                'required' => false,
                'widget' => 'single_text',
                'attr' => [
                    'max' => $today->format('Y-m-d')
                ]
            ])
            ->add('deathDate', BirthdayType::class, [
                'label' => 'Date de décès',
                'required' => false,
                'widget' => 'single_text',
                'attr' => [
                    'max' => $today->format('Y-m-d')
                ]
            ])
            ->add('biography', TextareaType::class, [
                'label' => 'Biographie',
                'required' => false,
                'attr' => [
                    'rows' => 10,
                ]
            ])
            ->add('save', SubmitType::class, ['label' => 'Sauvegarder']);
    }
}