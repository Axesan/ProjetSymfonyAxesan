<?php

namespace App\Form;

use App\Entity\Adress;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CountryType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AddressType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('name',TextType::class,[
                'label'=> 'Nom de votre Adresse ex: (Maison,Travail) *',
                'attr' => [
                    'placeholder' => 'Nom adresse ...' 
                ]
            ])
            ->add('firstname',TextType::class,[
                'label' => 'Prénom *',
                 'attr' => [
                    'placeholder' => 'Prénom ...' 
                ]
            ])
            ->add('lastname',TextType::class,[
                'label' => 'Nom ','attr' => [
                    'placeholder' => 'Nom ...' 
                ]
            ])
            ->add('company',TextType::class,[
                'label' => "Nom d'entreprise (Facultatif) ",
                'required'=>false,
                'attr' => [
                    'placeholder' => "Nom d'entreprise ..." 
                ]
            ])
            ->add('address',TextType::class,[
                'label' => "Votre adresse *",'attr' => [
                    'placeholder' => 'Adresse ...' 
                ]
            ])
            ->add('postal',TextType::class,[
                'label' => "Code postale * ",
                'attr' => [
                    'placeholder' => 'Pays ...' 
                    ]
                ])
            ->add('city',TextType::class,[
                'label' => "Ville *",
                'attr' => [
                    'placeholder' => 'Ville ...' 
                ]
            ])
            ->add('country',CountryType::class,[
                'label' => "Pays *",
                'attr' => [
                    'placeholder' => 'Pays ...' 
                ]
            ])
            ->add('phone',TelType::class,[
                'label' => "Numéros de téléphone *",
                'attr' => [
                    'placeholder' => 'Numéros de téléphone ...' 
                    ]
                ])
            ->add('submit',SubmitType::class,[
                'label'=> 'Ajouter une adresse','attr'=>[
                    'class'=> "btn btn-outline-danger"
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => Adress::class,
        ]);
    }
}
