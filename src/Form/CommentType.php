<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class CommentType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
            ->add('text', TextType::class, [
                'label' => 'Treść komentarza: ',
                'label_attr' => ['class' => 'registerLabelField'],
                'attr' => ['class' => 'registerEmptyField']
            ])
            ->add('save', SubmitType::class, array(
                'label' => 'Dodaj komentarz',
                'attr' => ['class' => 'registerSubmitButton'],
            ))
            ->getForm();
    }

}