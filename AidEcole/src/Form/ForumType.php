<?php  

namespace App\Form;  

use App\Entity\Forum;  
use Symfony\Component\Form\AbstractType;  
use Symfony\Component\Form\FormBuilderInterface;  
use Symfony\Component\OptionsResolver\OptionsResolver;  
use Symfony\Component\Form\Extension\Core\Type\FileType;  
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;  
use Symfony\Component\Form\Extension\Core\Type\DateType;  
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class ForumType extends AbstractType  
{  
    public function buildForm(FormBuilderInterface $builder, array $options): void  
    {  
        $builder  
        ->add('Date', DateType::class, [
            'widget' => 'single_text', 
            'constraints' => [
                new NotBlank(['message' => 'La date ne peut pas être vide.']),
            ],
        ])
        ->add('Questions', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'La question ne peut pas être vide.']),
                new Length([
                    'max' => 50,
                    'maxMessage' => 'La question ne peut pas dépasser {{ limit }} caractères.',
                ]),
            ],
        ])
        ->add('Description', TextType::class, [
            'constraints' => [
                new NotBlank(['message' => 'La description ne peut pas être vide.']),
                new Length([
                    'max' => 200,
                    'maxMessage' => 'La description ne peut pas dépasser {{ limit }} caractères.',
                ]),
            ],
        ])
            ->add('Images', FileType::class, [  
                'label' => 'Upload Image',  
                'mapped' => false,  
                'required' => false,  
                'attr' => ['class' => 'custom-file-input'],  
            ]) 
            
            ->add('Categorie', ChoiceType::class, [  
                'choices' => [  
                    'Cours' => 'cours',  
                    'Annonces' => 'annonces',  
                    'Signalements' => 'signalements',  
                ],  
                'placeholder' => 'Select a Category',  
                'expanded' => false,  
                'multiple' => false,  
                'constraints' => [
                    new NotBlank(['message' => 'La catégorie ne peut pas être vide.']),
                ],
             
            ]);  
    }  

    public function configureOptions(OptionsResolver $resolver): void  
    {  
        $resolver->setDefaults([  
            'data_class' => Forum::class,  
        ]);  
    }  
}