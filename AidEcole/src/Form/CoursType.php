<?php

namespace App\Form;
use Symfony\Component\Validator\Constraints\NotNull;
use App\Entity\Cours;
use App\Entity\Matiere;
use App\Entity\Niveau;
use App\Entity\PaiementCours;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\FileType;  
use Symfony\Component\Form\Extension\Core\Type\TextType; 
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\NotBlank;
use Vich\UploaderBundle\Form\Type\VichFileType;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\Event\PreSetDataEvent;
use Symfony\Component\Validator\Constraints as Assert;  



class CoursType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
    ->add('Titre', TextType::class, [
        'label' => 'Titre du cours'
    ])
    ->add('Description', TextType::class, [
        'label' => 'Description du cours',
        'required' => false, 
    ])

    ->addEventListener(FormEvents::PRE_SET_DATA, function (PreSetDataEvent $event) {
        $cours = $event->getData();
        $form = $event->getForm();

        if (!$cours || null === $cours->getId()) {
            $form->add('pdfFile', VichFileType::class, [
                'label' => 'Télécharger le cours (PDF uniquement)',
                'mapped' => false, 
                'required' => true,
                'allow_delete' => false,
                'constraints' => [
                    new NotBlank([
                        'message' => 'Veuillez sélectionner un fichier PDF.',
                    ]),
                    new File([
                        'mimeTypes' => ['application/pdf'],
                        'mimeTypesMessage' => 'Le fichier doit être au format PDF.',
                        'maxSize' => '20M',
                    ])
                ],
            ]);
        }
    })
    
            ->add('Prix')
            ->add('Matiere', EntityType::class, [
                'class' => Matiere::class,
                'choice_label' => 'nom',
                'placeholder' => 'Choisissez une matière',
                'label' => 'Matière',
                'constraints' => [
        new NotNull(['message' => 'Veuillez choisir une matière.']),],
            ])


            ->add('Niveau', EntityType::class, [  
                'class' => Niveau::class,  
                'choice_label' => 'Nom',  
                'placeholder' => 'Choisissez un niveau',  
                'label' => 'Niveau',
                'required' => true,
                'constraints' => [
        new NotNull(['message' => 'Veuillez choisir un niveau.']),],  
            ]);  


        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Cours::class,
        ]);
    }
}
