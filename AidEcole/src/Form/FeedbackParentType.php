<?php  

namespace App\Form;  

use App\Entity\Feedback;  
use Symfony\Bridge\Doctrine\Form\Type\EntityType;  
use Symfony\Component\Form\AbstractType;  
use Symfony\Component\Form\FormBuilderInterface;  
use Symfony\Component\OptionsResolver\OptionsResolver;  
use Symfony\Component\Form\Extension\Core\Type\TextareaType; 
use Symfony\Component\Form\Extension\Core\Type\TextType; // Ajout de l'import pour TextareaType
// Ajout de l'import pour TextareaType
use Symfony\Component\Form\Extension\Core\Type\IntegerType; // Ajout de l'import pour IntegerType
use Symfony\Component\Form\Extension\Core\Type\ChoiceType; // Ajout de l'import pour ChoiceType  

class FeedbackParentType extends AbstractType  
{  
    public function buildForm(FormBuilderInterface $builder, array $options): void  
    {  
        $builder  
            ->add('nom')  
            ->add('prenom')   
            ->add('mail')   

            ->add('avis1', TextType::class, [  
                'label' => 'Comment évaluez-vous la qualité des cours et des quiz proposés ?',  
                'required' => true,  
            ])  

            ->add('avis2', TextType::class, [  
                'label' => 'Êtes-vous satisfait des formations proposées sur notre plateforme ?',  
                'required' => true,  
            ])  

            ->add('avis3', TextType::class, [  
                'label' => 'Trouvez-vous que notre plateforme ou nos services répondent bien aux besoins de votre enfant ?',  
                'required' => true,  
            ])  
            ->add('avis12', TextType::class, [  
                'label' => 'Avez-vous des commentaires ou suggestions pour améliorer notre plateforme ?',  
                'required' => false,  
            ])  

            ->add('note', IntegerType::class, [
    'label' => 'Note',
    'required' => true,
    'attr' => [
        'min' => 1,
        'max' => 5,
        'class' => 'note-input', // Ajoutez une classe pour le champ caché
    ],
])

            /*->add('User_id', EntityType::class, [  
                'class' => User::class,  
                'choice_label' => 'id',  
                'label' => 'Utilisateur',  
            ])*/  

            ->add('experience_level', ChoiceType::class, [  
                'label' => 'Comment évaluez-vous votre niveau d\'expérience ?',  
                'choices' => [  
                    'Excellent' => 'excellent',  
                    'Bon' => 'bon',  
                    'Moyen' => 'moyen',  
                    'Mauvais' => 'mauvais',  
                ],  
                'placeholder' => 'Sélectionner une option',  
                'required' => true,  
            ])  
            
            ->add('recommend_platform', ChoiceType::class, [  
                'label' => 'Recommanderiez-vous notre plateforme à d\'autres ?',  
                'choices' => [  
                    'Oui' => 'oui',  
                    'Non' => 'non',  
                ],  
                'placeholder' => 'Sélectionner une option',  
                'required' => true,  
            ])  
            ->add('usage_frequency', ChoiceType::class, [  
                'label' => 'À quelle fréquence utilisez-vous notre plateforme ?',  
                'choices' => [  
                    'Quotidien' => 'quotidien',  
                    'Hebdomadaire' => 'hebdomadaire',  
                    'Mensuel' => 'mensuel',  
                    'Occasionnel' => 'occasionnel',  
                ],  
                'placeholder' => 'Sélectionner une option',  
                'required' => true,  
            ]);  
    }  

    public function configureOptions(OptionsResolver $resolver): void  
    {  
        $resolver->setDefaults([  
            'data_class' => Feedback::class,  
            'csrf_protection' => true,
        ]);  
    }  
}
