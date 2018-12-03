<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class NewPasswordType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
            ->add('password', PasswordType::class, [
                'label' => 'Nowe hasło: ',
                'label_attr' => ['class' => 'registerLabelField'],
                'attr' => ['class' => 'registerEmptyField']
            ])
            ->add('save', SubmitType::class, array(
                'label' => 'Zmień hasło',
                'attr' => ['class' => 'registerSubmitButton'],
            ))
            ->getForm();
    }

}