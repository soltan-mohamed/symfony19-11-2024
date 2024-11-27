<?php
namespace App\Form;

use App\Entity\Chambre;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;

class ChambreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero', TextType::class, [
                'label' => 'Numéro de Chambre',
                'required' => true,
            ])
            ->add('capacite', IntegerType::class, [
                'label' => 'Capacité de la Chambre',
                'required' => true,
                'attr' => [
                    'class' => 'form-control',
                    'min' => 0,
                ],
            ])
            ->add('foyer', ChoiceType::class, [
                'label' => 'Foyer',
                'choices' => $this->getFoyerChoices($options['foyers']),
                'choice_label' => function ($foyer) {
                    return $foyer->getNom();
                },
                'placeholder' => 'Choisissez un foyer',
                'required' => true,
            ])
            ->add('etat', ChoiceType::class, [
                'label' => 'État de la Chambre',
                'choices' => [
                    'Libre' => 'libre',
                    'Occupée' => 'occupée',
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('typeLit', ChoiceType::class, [
                'label' => 'Type de Lit',
                'choices' => [
                    'Simple' => 'simple',
                    'Double' => 'double',
                ],
                'expanded' => true,
                'multiple' => false,
                'required' => true,
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer',
            ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Chambre::class,
            'foyers' => [],
        ]);
    }

    private function getFoyerChoices(array $foyers)
    {
        $choices = [];
        foreach ($foyers as $foyer) {
            $choices[$foyer->getNom()] = $foyer;
        }
        return $choices;
    }
}
