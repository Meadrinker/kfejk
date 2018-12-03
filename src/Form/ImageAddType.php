<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Image;

class ImageAddType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
            ->add('picture', FileType::class, [
                'label' => 'Wybierz obrazek: ',
                'label_attr' => ['class' => 'registerLabelField'],
                'attr' => ['class' => 'registerEmptyField'],
                'constraints' => [
                    new Image([
                        'mimeTypes' => ['image/jpg', 'image/jpeg', 'image/png', 'image/bmp'],
                        'mimeTypesMessage' => 'Plik musi mieć rozszerzenie jpg, jpeg, png lub bmp',
                        'maxSize' => '1024k',
                        'maxSizeMessage' => 'Maksymalny dopuszczalny rozmiar pliku to 1024kb',
                    ])

                ],
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