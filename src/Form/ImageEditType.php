<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ImageEditType extends AbstractType {

    public function buildForm(FormBuilderInterface $builder, array $options) {

        $builder
            ->add('title', TextType::class, [
                'label' => 'Tytuł: ',
                'label_attr' => ['class' => 'registerLabelField'],
                'attr' => ['class' => 'registerEmptyField']
            ])
            ->add('tags', TextType::class, [
                'label' => 'Tagi: ',
                'label_attr' => ['class' => 'registerLabelField'],
                'data' => $options['tags'],
                'attr' => ['class' => 'registerEmptyField'],
                'mapped' => false
            ])
            ->add('accepted', ChoiceType::class, [
                'choices' => [
                    '1' => 1,
                    '0' => 0
                ],
                'label' => 'Akceptacja: ',
                'label_attr' => ['class' => 'registerLabelField'],
                'attr' => ['class' => 'registerEmptyField']
            ])
            ->add('save', SubmitType::class, array(
                'label' => 'Zapisz zmiany',
                'attr' => ['class' => 'registerSubmitButton'],
            ))
            ->getForm();
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
//            'data_class' => null,
            'tags' => null
        ));
    }

}