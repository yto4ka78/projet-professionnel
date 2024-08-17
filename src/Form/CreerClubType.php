<?php 

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichImageType;
use App\Entity\Club;

class CreerClubType extends AbstractType 
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add ('avatarFile', VichImageType::class, [
            'required' => false,
            'allow_delete' => false, 
            'download_uri' => false, 
            'image_uri' => false,
            'label' => false,
            'attr' => [
                'id' => 'custom-file-input',
                'class' => 'd-none'
            ],
        ])
        ->add ('backGroundAvatarFile', VichImageType::class, [
            'required' => false,
            'allow_delete' => false, 
            'download_uri' => false, 
            'image_uri' => false,
            'label' => false,
            'attr' => [
                'id' => 'custom-file-input',
                'class' => 'd-none'
            ],
        ])
        ->add('Name', TextType::class, [
            'constraints' => [
                new NotBlank([
                    'message' => 'Saisissez le prenom du club'
                ]),
                new Length([
                    'min' => 6,
                    'minMessage' => 'Votre prenom doit contenir au moins {{ limit }} caractères',
                ])],
            'label' => 'Le nom du club:'
            ])
        ->add('description', TextareaType::class, [
            'label' => 'Description',
            'constraints' => [
                new Length([
                    'min' => 50,
                    'minMessage' => 'Votre nom doit contenir au moins {{ limit }} caractères',
                    'max' => 1200,
                    'maxMessage' => 'Votre description ne peut pas dépasser {{ limit }} caractères',
            ])],
            'label' => 'Au propos de club:',
            'attr' => [
                'rows' => 6, 
            ],
        ])
        ->add('region', ChoiceType::class, [
            'choices' => [
                'Nouvelle Aquitaine' => 'Nouvelle Aquitaine',
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
        ->add('Owner', IntegerType::class, [
            'label' => 'Signifier le propriétaire (Par ID):',
            'attr' => [
                'id' => 'search_input',
            ],
            'label' => 'Signifier le propriétaire (Par ID):'
        ]);
    ;}

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Club::class,
        ]);
    }
}