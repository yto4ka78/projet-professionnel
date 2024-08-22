<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;

class SettingProfileFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Saisissez votre prenom',
                    ]),
                    new Length([
                        'min' => 6,
                        'minMessage' => 'Votre prenom doit contenir au moins {{ limit }} caractères',
                    ]),
                ],
                'label' => 'Votre Prenom'
            ])
            ->add('plainPassword', RepeatedType::class, [
                'type' => PasswordType::class,
                'first_options' => [
                    'constraints' => [
                        new NotBlank([
                            'message' => 'Saisissez le mot de pass',
                        ]),
                        new Length([
                            'min' => 6,
                            'minMessage' => 'Votre mot de pass doit contenir au moins {{ limit }} caractères',
                            'max' => 4096,
                        ]),
                    ],
                    'label' => 'Password',
                ],
                'second_options' => [
                    'label' => 'Repeat Password',
                ],
                'invalid_message' => 'Non concordance des mots de passe',
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
            ])
            ->add('description', TextType::class, [
                'label' => 'Description',
                'constraints' => [
                    new Length([
                        'min' => 20,
                        'minMessage' => 'Votre nom doit contenir au moins {{ limit }} caractères',
                ])],
            ])
    ;}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
