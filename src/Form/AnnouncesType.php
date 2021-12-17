<?php

namespace App\Form;

use App\Entity\Announces;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AnnouncesType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title', TextType::class, [
                'label' => 'Titre de votre annonce',
                'attr'=> [
                    'placeholder' => "Couple de retraité habitant à côté d'une grande forêt",
                ],
            ])
            ->add('description', TextType::class, [
                'label' => "Décrivez vous, votre logement, les alentours, vos habitudes avec les chiens...",
                'attr'=> [
                    'placeholder' => "Ex : Maison avec jardin. Nous réalisons 3 promenades par jour, dans la fôret ou autour du lac...",
                ],
            ])
            ->add('address', TextType::class, [
                'label' => "Adresse",
                'attr'=> [
                    'placeholder' => "Votre adresse",
                ],
            ])
            ->add('city', TextType::class, [
                'label' => "Ville",
                'attr'=> [
                    'placeholder' => "Votre ville",
                ],
            ])
            ->add('postcode', NumberType::class, [
                'label' => "Code Postal",
                'attr'=> [
                    'placeholder' => "Votre code postal",
                ],
                ])
            ->add('daily_price', NumberType::class, [
                'label' => "Prix journalier (en €)",
                'attr'=> [
                    'placeholder' => "€/jour",
                ],
            ])
            ->add('max_animals', ChoiceType::class, [
                'label' => "Combien d'animaux pouvez-vous accueillir chez vous?",
                'choices'  => [
                    '1' => 1,
                    '2' => 2,
                    '3' => 3,
                    '4' => 4,
                ],
            ])
            ->add('images', FileType::class, [
                'mapped' => false,
                'required' => false,
                'multiple' => false,
                'label' => "Uploader une image.",
                'attr'=> [
                    'placeholder' => "Parcourir les fichiers.",
                ],
                'constraints' => [
                    new File([
                        'maxSize' => "2048K",
                        'mimeTypes' => [
                            'image/png',
                            'image/jpg',
                            'image/jpeg',
                            'image/gif',
                        ],
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Announces::class,
        ]);
    }
}
