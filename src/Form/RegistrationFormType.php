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
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('firstname', TextType::class, [
            'label' => 'Prénom :',
            'attr' => ['class' => 'form-control',
                'placeholder' => 'Votre prénom'
            ]
        ])
        ->add('lastname', TextType::class, [
            'label' => 'Nom :',
            'attr' => ['class' => 'form-control',
                'placeholder' => 'Votre nom'
            ]
        ])
        ->add('address', TextType::class, [
            'label' => 'Adresse :',
            'attr' => ['class' => 'form-control',
                'placeholder' => 'Votre adresse'
            ]
        ])
        ->add('zipcode', TextType::class, [
            'label' => 'Code postal :',
            'attr' => ['class' => 'form-control',
                'placeholder' => 'Votre code postal'
            ]
        ])
        ->add('city', TextType::class, [
            'label' => 'Ville :',
            'attr' => ['class' => 'form-control',
                'placeholder' => 'Votre ville'
            ]
        ])
        ->add('email', EmailType::class, [
            'label' => 'Adresse électronique :',
            'attr' => ['class' => 'form-control',
                'placeholder' => 'Votre email'
            ]])
            ->add('RgpdConsent', CheckboxType::class, [
                'mapped' => false,
                'constraints' => [
                    new IsTrue([
                        'message' => 'You should agree to our terms.',
                    ]),
                ],
                'label' => 'J’ai lu la politique de protection des données de moderations paris, et j’accepte l’utilisation ​de mes données personnelles faites dans ce cadre.'
            ])
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                    'label' => 'Mot de passe :',
                    'invalid_message' => 'La confirmation du mot de passe doit être conforme au mot de passe',
                    'required' => true,
                    
                    'first_options' => [
                        'label' => 'Mot de passe :',
                        'attr' => ['class' => 'form-control',
                            'placeholder' => 'Votre mot de passe'
                        ]
                    ],
                    'second_options' => [
                        'label' => 'Confirmer mot de passe :',
                        'attr' => ['class' => 'form-control',
                            'placeholder' => 'Confirmez mot de passe'
                        ]
                    ]
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
