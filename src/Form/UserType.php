<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class UserType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {   //$repository = CityRepository::class;
        //$cities = $repository->findAll();
        $builder
            ->add('password', RepeatedType::class, [
                'type' => PasswordType::class,
                'invalid_message' => 'The password fields must match.',
                'options' => ['attr' => ['class' => 'password-field']],
                'required' => true,
                'first_options' => ['label' => 'Mot de passe : '],
                'second_options' => ['label' => 'confirmation du mot de passe : ']])
            ->add('username', TextType::class, array('label' => 'Identifiant : '))
            ->add('name', TextType::class, array('label' => 'Prénom : '))
            ->add('surname', TextType::class, array('label' => 'Nom : '))
            ->add('tel', TextType::class, array('label' => 'Téléphone : '))
            ->add('email', EmailType::class, array('label' => 'Email : '))
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'campusName'
            ]);


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class, City::class,

        ]);
    }
}
