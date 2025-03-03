<?php

namespace App\Form;

use App\Entity\Question;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\Quiz ; 

class QuestionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('title')
            ->add('options', TextareaType::class, [
                'label' => 'Options (JSON format)'
            ])
            ->add('correctAnswer')
            
        ;

        $builder->get('options')
        ->addModelTransformer(new CallbackTransformer(
            function ($array) {
                return json_encode($array, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT);
            },
            function ($jsonString) {
                return json_decode($jsonString, true, 512, JSON_THROW_ON_ERROR);
            }
        ));
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Question::class,
        ]);
    }
}
