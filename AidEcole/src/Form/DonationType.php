<?php

namespace App\Form;

use App\Entity\Donation;
use App\Entity\Signalement;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class DonationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('date_valid', null, [
                'widget' => 'single_text',
            ])
            ->add('date', null, [
                'widget' => 'single_text',
            ])
            ->add('montant')
            ->add('donnateur', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])
            ->add('signalement', EntityType::class, [
                'class' => Signalement::class,
                'choice_label' => 'id',
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Donation::class,
        ]);
    }
}
