<?php
namespace App\Form;  

use App\Entity\Niveau;  
use Symfony\Component\Form\AbstractType;  
use Symfony\Component\Form\Extension\Core\Type\TextType;  
use Symfony\Component\Form\Extension\Core\Type\FileType;  
use Symfony\Component\Form\Extension\Core\Type\SubmitType;  
use Symfony\Component\Form\FormBuilderInterface;  
use Symfony\Component\OptionsResolver\OptionsResolver;  

class NiveauType extends AbstractType  
{  
    public function buildForm(FormBuilderInterface $builder, array $options)  
    {  
        $builder  
            ->add('Nom', TextType::class, [  
                'label' => 'Nom du niveau',  
                'required' => true,  
            ])  
           ;
    }  

    public function configureOptions(OptionsResolver $resolver)  
    {  
        $resolver->setDefaults([  
            'data_class' => Niveau::class,  
        ]);  
    }  
}