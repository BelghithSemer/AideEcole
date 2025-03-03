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

class FeedbackType extends AbstractType  
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
            
            ->add('avis4', TextType::class, [  
                'label' => 'Avez-vous rencontré des difficultés pour faire un don ?',  
                'required' => true,  
            ])  
            
            ->add('avis5', TextType::class, [  
                'label' => 'Êtes-vous satisfait(e) des progrès réalisés dans l\'amélioration des écoles ?',  
                'required' => true,  
            ])  

            ->add('avis6', TextType::class, [  
                'label' => 'Comment évaluez-vous le processus de publication d\'une annonce de formation sur notre plateforme ?',  
                'required' => true,  
            ])  

            ->add('avis7', TextType::class, [  
                'label' => 'Pensez-vous que notre plateforme vous aide à atteindre votre public cible plus efficacement ?',  
                'required' => true,  
            ])  
            

            
            ->add('avis8', TextType::class, [  
                'label' => '•	Avez-vous rencontré des difficultés lors de la soumission d\'un signalement ?',  
                'required' => true,  
            ])  

            ->add('avis9', TextType::class, [  
                'label' => 'Cette fonctionnalité vous a-t-elle aidé à mieux gérer les problèmes ou les incidents au sein de votre école ?',  
                'required' => false,  
            ])  

            ->add('avis10', TextType::class, [  
                'label' => 'Trouvez-vous facile de gérer et d\'organiser vos cours et quiz sur la plateforme ?',  
                'required' => false,  
            ])  

            ->add('avis11', TextType::class, [  
                'label' => 'Trouvez-vous les outils de suivi des performances des élèves (résultats des quiz, progression dans les cours) utiles et complets ?',  
                'required' => false,  
            ])  

            
            ->add('avis12', TextType::class, [  
                'label' => 'Avez-vous des commentaires ou suggestions pour améliorer notre plateforme ?',  
                'required' => false,  
            ])  

            ->add('note', IntegerType::class, [  
                'label' => 'Note',  
                'required' => true,  
                'attr' => ['min' => 1, 'max' => 5], // Limiter la note de 1 à 5  
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
