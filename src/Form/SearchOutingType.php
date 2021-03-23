<?php


namespace App\Form;

use App\Data\SearchData;
use App\Entity\Campus;
use Doctrine\ORM\EntityRepository;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use \Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\DateTime;

class SearchOutingType extends AbstractType
{

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     * Fonction qui sert à créer notre formulaire de recherche
     * Aucun champs n'est requis (required false) puisqu'on n'est pas obligé d'utiliser le formulaire de recherche
     * pour afficher les sorties.
     * 'expanded' et 'multiple' permettent d'avoir une liste de checkbox, tandis que choice_label va permettre d'avoir
     * une liste déroulante de choix (avec un seul choix, à l'inverse des checkbox)
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('q', TextType::class, [
                'label' => false,
                'required' => false,
                'attr' => [
                    'placeholder' => 'Rechercher une sortie par mot-clé'
                    ]
            ])

            ->add('campus', EntityType::class, [
                'label' => 'Campus :',
                'required' => false,
                'class' => Campus::class,
                'expanded' => true,
                'multiple' => true
//                'choice_label' => function (Campus $camp) {
//                $camp->getCampusName();
//                },
            ])


            ->add('mindate', DateTimeType::class, [
                'label' => 'Date minimum',
                'required' => false,
            ])

            ->add('maxdate', DateType::class, [
                'label' => 'Date maximum',
                'required' => false
            ])
/*
            ->add('organizer', CheckboxType::class, [
                'label' => 'Sorties dont je suis l\'organisateur',
                'required' => false
            ])

            ->add('participant', CheckboxType::class, [
                'label' => 'Sorties auxquelles je participe',
                'required' => false
            ])

            ->add('notParticipant', CheckboxType::class, [
                'label' => 'Sorties auxquelles je ne participe pas',
                'required' => false
            ])

            ->add('pastOutings', CheckboxType::class, [
                'label' => 'Sorties passées',
                'required' => false
            ])
*/
;



    }


    /**
     * @param OptionsResolver $resolver
     * Permet de configurer les différentes options liées à ce formulaire
     */
    public function configureOptions(OptionsResolver $resolver) {

        $resolver->setDefaults([
            'data_class' => SearchData::class,
            'method' => 'GET',
            //csrf_protection : on la désactive car dans un formulaire de recherche : pas de soucis de cross-scripting
            'csrf_protection' => false
        ]);
    }

    /**
     * @return string
     * Par défaut la fonction retourne un tableau qui s'appelle SearchData avec toutes les données
     * On ne veut pas de ce préfix, donc un return '' pour avoir un url le plus propre possible
     * on retourne ainsi une simple chaine de caractères vide
     */
    public function getBlockPrefix()
    {
        //par défaut :
        //return parent::getBlockPrefix();

        return '';
    }

}