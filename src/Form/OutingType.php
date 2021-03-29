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
    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * Méthode en charge de générer le formulaire de création d'une sortie, avec deux boutons de soumissions :
     * 1er champ : enregistrer la sortie en base sans la publier.
     * 2ème champ : enregistrer la sortie en base et la publier sur la page d'accueil.
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array('label' => 'Nom :'))
            ->add('startingDateTime', DateTimeType::class, array('label' => 'Date & heure de la sortie :',
                'date_widget'=> 'single_text',
                'with_minutes' => true,
                'input' => 'datetime',))
            ->add('duration', TimeType::class, array('label' => 'Durée :',
                'widget'=> 'single_text'))
            ->add('registrationDeadLine', DateType::class, array('label' => 'Date limite d\'inscription :',
                'widget'=> 'single_text'))
            ->add('maxNumberRegistration', IntegerType::class, array('label' => 'Nombre de places :'))
            ->add('outingInfos', TextareaType::class, array('label' => 'Informations :'))
            ->add('location', EntityType::class, array('class' => Location::class, 'label' => 'Nom du lieu :'))
            ->add('save', SubmitType::class, array('label' => 'Enregistrer la sortie'))
            ->add('saveAndAdd', SubmitType::class, array('label' => 'Publier la sortie'));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Outing::class,
        ]);
    }
}
