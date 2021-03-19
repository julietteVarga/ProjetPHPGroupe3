<?php

namespace App\Form;

use App\Entity\Campus;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SignUpType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('password', PasswordType::class)
            ->add('username', TextType::class)
           ->add('name')
            ->add('surname')
            ->add('tel')
            ->add('email')
            ->add('isActive', CheckboxType::class, [
                'label' => 'Etudiant actif'
            ])
            ->add('roles', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'choices' => [
                    'User' => 'ROLE_USER',
                    'Admin' => 'ROLE_ADMIN',
                ]
            ])
            //->add('campus', EntityType::class, [
            //    'class' => Campus::class,
            //    'choice_label' => 'campusName',
            //])
        ;

        //Data transformer :
        $builder->get('roles')
            ->addModelTransformer(new CallbackTransformer(
                function ($rolesArray) {
                    //transform the array to a string :
                    return count($rolesArray)? $rolesArray[0]: null;
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
