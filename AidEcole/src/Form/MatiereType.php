<?php

namespace App\Form;

use App\Entity\Matiere;
use App\Entity\Niveau;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class MatiereType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Nom')
            ->add('Niveau', EntityType::class, [
                'class' => Niveau::class,
                'choice_label' => 'nom',
                'multiple' => true,
                'expanded' => true,
            ])
            ->add('description', TextType::class, [
                'label' => 'Description de l\'annonce',
            ])
            ->add('image', FileType::class, [
                'label' => 'Image de l\'annonce',
                'required' => false,
                'mapped' => false,
            ])
            ->add('nbrePlace', IntegerType::class, [
                'label' => 'Nombre de places disponibles',
            ])
            ->add('location', TextType::class, [
                'label' => 'Localisation',
            ]); // Added missing semicolon here
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Matiere::class,
        ]);
    }
}