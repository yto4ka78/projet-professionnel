<?php

use Vich\UploaderBundle\Form\Type\VichImageType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\AbstractType;

class AvatarClubUploadFile extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
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
