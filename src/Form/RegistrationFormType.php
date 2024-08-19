<?php

namespace App\Form;

use App\Entity\Users;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\Regex;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Champ Prénom
            ->add('firstname', TextType::class, [
                'label' => 'Prénom :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre prénom',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre prénom doit comporter au moins {{ limit }} caractères',
                        'max' => 50,
                    ]),
                ],
                'attr' => [
                ]
            ])
            // Champ Nom
            ->add('lastname', TextType::class, [
                'label' => 'Nom :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre nom',
                    ]),
                    new Length([
                        'min' => 2,
                        'minMessage' => 'Votre nom doit comporter au moins {{ limit }} caractères',
                        'max' => 50,
                    ]),
                ],
                'attr' => [
                   
                ]
            ])
            // Champ Adresse
            ->add('address', TextType::class, [
                'label' => 'Adresse :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre adresse',
                    ]),
                ],
                'attr' => [
                 
                ]
            ])
            // Champ Code postal
            ->add('zipcode', TextType::class, [
                'label' => 'Code postal :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre code postal',
                    ]),
                    new Regex([
                        'pattern' => '/^\d{5}$/',
                        'message' => 'Veuillez entrer un code postal valide',
                    ]),
                ],
                'attr' => [
                   
                ]
            ])
            // Champ Ville
            ->add('city', TextType::class, [
                'label' => 'Ville :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre ville',
                    ]),
                ],
                'attr' => [
                   
                ]
            ])
            // Champ Adresse électronique
            ->add('email', EmailType::class, [
                'label' => 'Adresse électronique :',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez entrer votre adresse électronique',
                    ]),
                ],
                'attr' => [
                    
                ]
            ])
            // Consentement RGPD
            ->add('RgpdConsent', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'Vous devez accepter les conditions.',
                    ]),
                ],
                'label' => 'J’ai lu la politique de protection des données de moderations paris, et j’accepte l’utilisation ​de mes données personnelles faites dans ce cadre.'
            ])
            // Champ Mot de passe
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les champs du mot de passe doivent correspondre.',
                'required' => true,
                'first_options' => [
                    'label' => 'Mot de passe',
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Veuillez entrer un mot de passe',
                        ]),
                        new Length([
                            'min' => 8,
                            'minMessage' => 'Votre mot de passe doit comporter au moins {{ limit }} caractères',
                            'max' => 4096,
                        ]),
                    ],
                ],
                'second_options' => [
                    'label' => 'Confirmer Mot de passe',
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Users::class,
        ]);
    }
}