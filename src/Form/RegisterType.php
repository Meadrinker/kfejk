<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegisterType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
            ->add('username', TextType::class, [
                'label' => 'Nazwa użytkownika: ',
                'label_attr' => ['class' => 'registerLabelField'],
                'attr' => ['class' => 'registerEmptyField']
            ])
            ->add('password', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Oba pola muszą mieć takie same hasła',
                'options' => array('attr' => array('class' => 'registerEmptyField')),
                'required' => true,
                'first_options' => array('label' => 'Hasło: ',
                    'label_attr' => ['class' => 'registerLabelField']),
                'second_options' => array('label' => 'Powtórz hasło: ',
                    'label_attr' => ['class' => 'registerLabelField']),
                'constraints' => array(
                    new NotBlank(),
                    new Length(array(
                        'min' => 3,
                        'max' => 20,
                        'minMessage' => "Haslo musi skladac sie z przynajmniej {{ limit }} znakow",
                        'maxMessage' => "Maksymalna liczba znakow w hasle to {{ limit }}"
                    )),
                ),
                'mapped' => false

            ))
            ->add('email', RepeatedType::class, array(
                'type' => TextType::class,
                'invalid_message' => 'Oba pola muszą mieć takie same e-maile',
                'options' => array('attr' => array('class' => 'registerEmptyField')),
                'required' => true,
                'first_options' => array('label' => 'E-mail: ',
                    'label_attr' => ['class' => 'registerLabelField']),
                'second_options' => array('label' => 'Powtórz e-mail: ',
                    'label_attr' => ['class' => 'registerLabelField']),
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Stworz konto',
                'attr' => ['class' => 'registerSubmitButton'],
            ))
            ->getForm();
    }

}