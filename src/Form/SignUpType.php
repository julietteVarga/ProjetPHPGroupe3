<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignUpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
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
            ->add('isActive', CheckboxType::class, [
                'label' => 'Etudiant en formation'
            ])
            ->add('roles', ChoiceType::class, [
                'label' => 'Role : ',
                'required' => true,
                'multiple' => false,
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ]
            ])
            ->add('campus', EntityType::class, [
                'label' => 'Campus :',
                'class' => Campus::class,
                'choice_label' => 'campusName',
            ]);

        //Data transformer :
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    //transform the array to a string :
                    return count($rolesArray) ? $rolesArray[0] : null;
                },
                function ($rolesString) {
                    //transform the string back to an array :
                    return [$rolesString];
                }
            ));

    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
