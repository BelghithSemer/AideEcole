<?php

namespace App\Form;

use App\Entity\Signalement;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;  // Correct import
use Symfony\Component\Form\Extension\Core\Type\FileType;  // Import FileType
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\FormBuilderInterface;

class SignalementType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('titre', TextType::class, [  // Correct usage of TextType
                'label' => 'Titre du signalement',
            ])
            ->add('description', TextareaType::class, [
                'label' => 'Description du signalement',
            ])
            ->add('prix', NumberType::class, [
                'label' => 'Prix DT',
            ])
            ->add('images', FileType::class, [
                'label' => 'Images du signalement',
                'multiple' => true,
                'mapped' => false,  // Non lié directement à l'entité
                'required' => false,
            ]);

        // If the signalement exists (edit mode), display the 'etat' field
        if ($options['is_edit']) {
            $builder->add('etat', ChoiceType::class, [
                'choices' => [
                    'En attente' => 'en attente',
                    'En cours' => 'en cours',
                    'Terminé' => 'terminé',
                ],
                'label' => 'État du signalement',
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Signalement::class,
            'is_edit' => false,  // Default is add mode
        ]);
    }
}
