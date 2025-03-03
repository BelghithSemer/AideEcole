<?php
// src/Form/SignalementTermineType.php
// src/Form/SignalementTermineType.php

namespace App\Form;

use App\Entity\SignalementTermine;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignalementTermineType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            // Ajouter un champ pour les images "après"
            ->add('imagesApres', FileType::class, [
                'label' => 'Images après la modification',
                'multiple' => true,
                'mapped' => false,
                'required' => false,
                'attr' => ['accept' => 'image/*'],
            ])
            // Ajouter un champ pour la description
            ->add('description', TextType::class, [
                'label' => 'Description des modifications',
                'required' => true,
            ]);
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => SignalementTermine::class,
        ]);
    }
}
