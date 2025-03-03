<?php

namespace App\Form;

use App\Entity\Annonce;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class AnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Titre', TextType::class, [
                'label' => 'Titre de l\'annonce',
            ])
            ->add('Description', TextType::class, [
                'label' => 'Description de l\'annonce',
            ])
            ->add('Image', FileType::class, [
                'label' => 'Image de l\'annonce',
                'required' => false,
                'mapped' => false,
            ])
            ->add('NbrePlace', IntegerType::class, [
                'label' => 'Nombre de places disponibles',
            ])
            ->add('Location', TextType::class, [
                'label' => 'Localisation',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Annonce::class,
        ]);
    }
}