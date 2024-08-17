<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
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
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Please enter your name',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Your name should be at least {{ limit }} characters',
                    ]),
                ],
                'label' => 'Votre Prenom'
            ])
            ->add('email', EmailType::class, [
                'label' => 'Email adresse'
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Please enter a password',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Your password should be at least {{ limit }} characters',
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'Password',
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                ],
                'invalid_message' => 'The password fields must match.',
                'mapped' => false,
            ])
            ->add('region', ChoiceType::class, [
                'choices' => [
                    'Nouvelle Aquitaine' => 'nouvelle_aquitaine',
                    'Bretagne' => 'bretagne',
                    'Bourgogne' => 'bourgogne',
                    'Normandie' => 'normandie',
                    'Île-de-France' => 'ile_de_france',
                    'Grand-Est' => 'grand_est',
                    'Auvergne Alpes' => 'auvergne_alpes',
                    'Pays de la Loire' => 'pays_de_la_loire',
                    'Hauts-de-France' => 'hauts_de_france',
                    'Val de Loire' => 'val_de_loire',
                    'Occitanie' => 'occitanie',
                    'Corse' => 'corse',
                    'Provence' => 'provence_alpes',
                    'Marseille' => 'marseille',
                ],
                'label' => 'Region',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
