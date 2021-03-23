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
            ->add('name', TextType::class, array('label' => 'Nom :'))
            ->add('startingDateTime', DateTimeType::class, array('label' => 'Date & heure de la sortie :'))
            ->add('duration', TimeType::class, array('label' => 'Durée :'))
            ->add('registrationDeadLine', DateType::class, array('label' => 'Date limite d\'inscription :'))
            ->add('maxNumberRegistration', IntegerType::class, array('label' => 'Nombre de places :'))
            ->add('outingInfos', TextareaType::class, array('label' => 'Informations :'))
            ->add('location', EntityType::class, array('class' => Location::class))
            ->add('save', SubmitType::class, array('label' => 'Enregistrer'))
            ->add('saveAndAdd', SubmitType::class, array('label' => 'Publier'));


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Outing::class,
        ]);
    }
}
