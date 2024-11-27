<?php

namespace App\Form;

use App\Entity\Foyer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;


class FoyerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Nom du Foyer',
                'required' => true,
            ])
            ->add('adresse', TextType::class, [ 
                'label' => 'Adresse',
                'required' => true,
            ])
            ->add('genre', ChoiceType::class, [
                'label' => 'Genre',
                'choices' => [
                    'Garçons' => 'Garçons',
                    'Filles' => 'Filles',
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('gouvernorat', ChoiceType::class, [
                'label' => 'Gouvernorat',
                'choices' => [
                    'Gouvernorat de Tunis' => 'Gouvernorat de Tunis',
                    'Gouvernorat de Ariana' => 'Gouvernorat de Ariana',
                    'Gouvernorat de Ben Arous' => 'Gouvernorat de Ben Arous',
                    'Gouvernorat de Manouba' => 'Gouvernorat de Manouba',
                ],
                'placeholder' => 'Choisissez une zone',
                'required' => true,
            ])
            ->add('capacite', IntegerType::class, [
                'label' => 'Capacité',
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                ],
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Foyer::class,
        ]);
    }
}