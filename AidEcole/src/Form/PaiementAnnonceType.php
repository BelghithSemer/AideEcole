<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\PaiementAnnonce;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;


class PaiementAnnonceType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Prix')
            ->add('numCarte')
            ->add('dateValidite', DateType::class, [
                'widget' => 'single_text',
                'format' => 'MM/yy',
                'html5' => false,
            ])
            

            /*->add('PaiementAnnonce_id', EntityType::class, [
                'class' => User::class,
                'choice_label' => 'id',
            ])*/
            /*->add('Annonce_id', EntityType::class, [
                'class' => Annonce::class,
                'choice_label' => 'id',
            ])*/
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => PaiementAnnonce::class,
        ]);
    }
}
