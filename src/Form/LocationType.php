<?php

namespace App\Form;

use App\Entity\City;
use App\Entity\Location;
use Doctrine\DBAL\Types\FloatType;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class LocationType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * Méthode en charge de générer le formulaire d'ajout de lieu.
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('city', EntityType::class, [
                'class' => City::class,
                'choice_label' => 'name',
                'label' => 'Ville'
            ])
            ->add('name', TextType::class, array('label' => 'Nom du lieu :'))
            ->add('street', TextType::class, array('label' => 'Rue :'))
            ->add('longitude', TextType::class, array('label' => 'Longitude :'))
            ->add('latitude', TextType::class, array('label' => 'Latitude :'))
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Location::class,
        ]);
    }
}
