<?php

namespace App\Form;

use App\Entity\Location;
use App\Entity\Outing;

use Doctrine\ORM\Mapping\Entity;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\IntegerType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OutingType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, [
                'label' => 'Nom :'
                ])
            ->add('startingDateTime', DateTimeType::class, [
                    'label' => 'Date & heure de la sortie :',
                    'date_widget'=> 'single_text',
                    'with_minutes' => true,
                    'input' => 'datetime',
            ])
            ->add('duration', TimeType::class, [
                    'label' => 'Durée :',
                    'widget'=> 'single_text'
            ])

            ->add('registrationDeadLine', DateType::class, [
                'label' => 'Date limite d\'inscription :',
                'widget'=> 'single_text'
            ])

            ->add('maxNumberRegistration', IntegerType::class, [
                'label' => 'Nombre de places :'
            ])
            ->add('outingInfos', TextareaType::class, [
                'label' => 'Informations :'
            ])
            ->add('location', EntityType::class, [
                'class' => Location::class
            ])
            ->add('save', SubmitType::class, [
                'label' => 'Enregistrer'
            ])
            ->add('saveAndAdd', SubmitType::class, [
                'label' => 'Publier'
            ]);

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Outing::class,
        ]);
    }
}
