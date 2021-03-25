<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\City;
use App\Entity\User;
use Gedmo\Uploadable\Uploadable;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\SubmitButton;
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
                'first_options' => ['label' => 'Mot de passe'],
                'second_options' => ['label' => 'confirmation du mot de passe'],])
            ->add('username')
            ->add('name')
            ->add('surname')
            ->add('tel')
            ->add('email')
            ->add('campus', EntityType::class, [
                'class' => Campus::class,
                'choice_label' => 'campusName'
            ])
            ->add('profilePic', FileType::class,[
                'data_class'=>null,
                'required' => false
            ])

        ;


    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,

        ]);
    }
}
