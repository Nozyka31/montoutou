<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', EmailType::class, [
                'label' => "Votre adresse mail",
                'required' => true,
                'attr' => [
                    'placeholder' => "Entrez votre adresse mail",
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champ ne doit pas être vide",
                    ]),
                    new Email([
                        'message' => "L'adresse mail doit être une adresse valide."
                    ])
                    ],
            ])
            ->add('plainPassword', PasswordType::class, [
                // instead of being set onto the object directly,
                // this is read and encoded in the controller
                'mapped' => false,
                'label' => "Votre mot de passe",
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new NotBlank([
                        'message' => 'Vous devez entrer un mot de passe',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre mot de passe doit contenir plus de {{ limit }} caractères',
                        // max length allowed by Symfony for security reasons
                        'max' => 4096,
                    ]),
                ],
            ])
            ->add('firstName', TextType::class, [
                'label' => "Votre prénom",
                'required' => true,
                'attr' => [
                    'placeholder' => "Entrez votre prénom",
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champ ne doit pas être vide",
                    ]),
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => "Votre nom",
                'required' => true,
                'attr' => [
                    'placeholder' => "Entrez votre nom",
                ],
                'constraints' => [
                    new NotBlank([
                        'message' => "Ce champ ne doit pas être vide",
                    ]),
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
