<?php
namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\File;  
use Symfony\Component\Validator\Constraints as Assert;

class RegistrationFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('email', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L\'adresse e-mail est obligatoire.']),
                    new Assert\Email(['message' => 'Veuillez entrer une adresse e-mail valide.']),
                ],
            ])
            ->add('nom', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom est obligatoire.']),
                    new Assert\Length(['min' => 2, 'max' => 50, 'minMessage' => 'Le nom doit contenir au moins 2 caractères.']),
                ],
            ])
            ->add('prenom', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le prénom est obligatoire.']),
                    new Assert\Length(['min' => 2, 'max' => 50, 'minMessage' => 'Le prénom doit contenir au moins 2 caractères.']),
                ],
            ])
            ->add('tel', TextType::class, [
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le numéro de téléphone est obligatoire.']),
                    new Assert\Regex([
                        'pattern' => '/^\d{8,15}$/',
                        'message' => 'Le numéro de téléphone doit contenir entre 8 et 15 chiffres.',
                    ]),
                ],
            ]);

          if (!$options['isProfileEdit']) {
            $builder->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'Les mots de passe doivent correspondre.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options'  => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'Confirmer le mot de passe'],
                'attr' => ['autocomplete' => 'new-password'],
                'constraints' => [
                    new Assert\NotBlank(),
                    new Assert\Length(['min' => 8]),
                    new Assert\Regex([
                        'pattern' => '/[A-Z]/',
                        'message' => 'Le mot de passe doit contenir au moins une majuscule.',
                    ]),
                    new Assert\Regex([
                        'pattern' => '/[!@#$%^&*(),.?":{}|<>]/',
                        'message' => 'Le mot de passe doit contenir au moins un symbole.',
                    ]),
                ],
            ]);
        }
 
            if (!$options['isProfileEdit']) {
            $builder->add('agreeTerms', CheckboxType::class, [
                'label' => "J'accepte les termes et conditions",
                'required' => true,
                'constraints' => [
                    new Assert\IsTrue(['message' => 'Vous devez accepter les termes et conditions.']),
                ],
            ]);
        }
        
        if (!$options['isProfileEdit']) {
            $builder->add('sexe', ChoiceType::class, [
                'choices' => [
                    'Homme' => 'Homme',
                    'Femme' => 'Femme',
                ],
                'expanded' => true,
                'multiple' => false,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Veuillez sélectionner votre sexe.']),
                ],
            ]);
        }
   
        
    

        if (isset($options['role']) && $options['role'] === 'responsable_etablissement') {
            $builder->add('nom_etabli', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom de l’établissement est obligatoire.']),
                ],
            ])
            ->add('rib', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le RIB bancaire est obligatoire.']),
                    new Assert\Regex([
                        'pattern' => '/^\d{16,24}$/',
                        'message' => 'Le RIB doit contenir entre 16 et 24 chiffres.',
                    ]),
                ],
            ])
            ->add('doc_verif', FileType::class, [
                'label' => 'Document de vérification (PDF, JPG, PNG)',
                'mapped' => false,
                'required' => false,
                'constraints' => [
                    new File([
                        'maxSize' => '5M',
                        'mimeTypes' => ['application/pdf', 'image/jpeg', 'image/png'],
                        'mimeTypesMessage' => 'Veuillez télécharger un fichier PDF, JPG ou PNG valide.',
                    ]),
                    new Assert\NotBlank(['message' => 'Veuillez fournir un document de vérification.']),
                ],
            ]);
        }

        if (isset($options['role']) && $options['role'] === 'centre_formation') {
            $builder->add('nom_form', TextType::class, [
                'required' => false,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le nom du centre de formation est obligatoire.']),
                ],
            ]);
        }

        if (isset($options['role']) && $options['role'] === 'parent') {
            $builder->add('code_postal', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'Le code postal est obligatoire.']),
                    new Assert\Regex([
                        'pattern' => '/^\d{4,6}$/',
                        'message' => 'Le code postal doit contenir entre 4 et 6 chiffres.',
                    ]),
                ],
            ])
            ->add('local', TextType::class, [
                'required' => true,
                'constraints' => [
                    new Assert\NotBlank(['message' => 'L’adresse est obligatoire.']),
                ],
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver): void
{
    $resolver->setDefaults([
        'data_class' => User::class,
        'isProfileEdit' => false,
        'role' => 'user',
        'isChangePassword' => false,
    ]);
}

}
