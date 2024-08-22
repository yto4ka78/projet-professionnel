<?php 

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Vich\UploaderBundle\Form\Type\VichImageType;



class AvatarUploadFile extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        ->add('avatarFile', VichImageType::class, [
            'required' => false,
            'allow_delete' => false, 
            'download_uri' => false, 
            'image_uri' => false,
            'label' => false,
            'attr' => [
                'id' => 'custom-file-input',
                'class' => 'd-none'
            ],
        ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([]);
    }
}