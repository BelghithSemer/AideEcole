<?php

namespace App\Form;

use App\Entity\Reclamation;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;   
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

class ReclamationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
        
            ->add('nom', TextType::class, [
                'label' => 'Entrer votre nom*',
                'required' => true,
                
            ])
            ->add('prenom', TextType::class, [
                'label' => 'Entrer votre prénom*',
                'required' => true,
                
            ])
            ->add('Sujet', TextType::class, [
                'label' => 'Sujet*',
                'required' => true,
                
            ])
            ->add('Description', TextType::class, [
                'label' => 'Entrez votre message ici*',
                'required' => true,
               
            ])

            ->add('email', TextType::class, [
                'label' => 'Entrez votre adresse mail ici*',
                'required' => true,
               
            ]);


    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Reclamation::class,
        ]);
    }
}
