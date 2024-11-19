<?php

namespace App\Form;

use App\Entity\Foyer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FoyerType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nom', TextType::class, [
                'label' => 'Foyer Name',
                'required' => true,
            ])
            ->add('adresse', TextType::class, [ 
                'label' => 'Adresse',
                'required' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Create Foyer'
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Foyer::class,
        ]);
    }
}