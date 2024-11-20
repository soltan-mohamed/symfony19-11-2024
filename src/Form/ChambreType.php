<?php
namespace App\Form;

use App\Entity\Chambre;
use App\Entity\Foyer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;


class ChambreType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('numero', TextType::class)
            ->add('capacite', IntegerType::class)
            ->add('foyer', ChoiceType::class, [
                'choices' => $this->getFoyerChoices($options['foyers']),
                'choice_label' => function (Foyer $foyer) {
                    return $foyer->getNom();
                },
                'placeholder' => 'Choose a foyer',
                'required' => true,
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
