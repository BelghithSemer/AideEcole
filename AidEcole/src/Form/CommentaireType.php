<?php

namespace App\Form;

use App\Entity\Annonce;
use App\Entity\Commentaire;
use App\Entity\Cours;
use App\Entity\Signalement;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

class CommentaireType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('Description', TextareaType::class, [
                'label' => 'Votre commentaire',
                'attr' => ['rows' => 5],
            ]);
            
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Commentaire::class,
            'annonce_id' => null,

        ]);
    }
}
