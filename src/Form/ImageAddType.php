<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class ImageAddType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
            ->add('picture', FileType::class, [
                'label' => 'Wybierz obrazek: ',
                'label_attr' => ['class' => 'registerLabelField'],
                'attr' => ['class' => 'registerEmptyField'],
                'mapped' => false
            ])
            ->add('title', TextType::class, [
                'label' => 'Tytuł: ',
                'label_attr' => ['class' => 'registerLabelField'],
                'attr' => ['class' => 'registerEmptyField']
            ])
            ->add('tags', TextType::class, [
                'label' => 'Tagi: ',
                'label_attr' => ['class' => 'registerLabelField'],
                'attr' => ['class' => 'registerEmptyField'],
                'mapped' => false
            ])
            ->add('save', SubmitType::class, array(
                'label' => 'Dodaj obrazek',
                'attr' => ['class' => 'registerSubmitButton'],
            ))
            ->getForm();
    }

}