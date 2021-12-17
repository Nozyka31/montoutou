<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;


class SearchType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('city', TypeTextType::class, [
                'label' => "Ville",
                'required' => true,
                'attr' => [
                    'placeholder' => "Entrez le nom de le ville ou vous souhaitez faire garder votre annimal",
                ],
            ])
            ->add('search', SubmitType::class, [
                'attr' => [
                    'class' => 'btn primary'
                ],
            ])
            ->getForm();
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Announces::class,
        ]);
    }
}
