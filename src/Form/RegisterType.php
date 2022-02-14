<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
// Les types d'input 
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
// EO Les types d'input 
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class RegisterType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        /**
         * 
         * Dans "add('')" on peut ajouter plusieur parametres par exemple au lieux d'afficher "lastname" on affiche "Votre prenom" 
         * Nous pouvons egalement ajouter des class pour le css :  'attr' => ['placeholder' => 'Entrez votre prénom ...','class' => 'btn']
         * 
         */
        $builder
            // Ici on peut modifier notre formulaire
            ->add('firstname',TextType::class,[
                // Contrainte doit comporter entre 2 charactere et 30 charactere
                'constraints'=> new Length([
                    'min' => 2,
                    'max' => 30
                ]),
                'label' => "Votre prénom",
                'attr' => [
                    'placeholder' => 'Entrez votre prénom ...'
                ]
            ])
            ->add('lastname',TextType::class,[
                 // Contrainte doit comporter entre 2 charactere et 30 charactere
                 'constraints'=> new Length([
                    'min' => 2,
                    'max' => 30
                ]),
                'label' => "Votre nom",
                'attr' => [
                    'placeholder' => 'Entrez votre nom ...'
                ]
            ])
            ->add('email',EmailType::class,[
                 // Contrainte doit comporter entre 2 charactere et 30 charactere
                 'constraints'=> new Length([
                    'min' => 2,
                    'max' => 60
                ]),
                'label' => "Votre email",
                'attr' => [
                    'placeholder' => 'exemple@exemple.com'
                ]
            ]) // Ceci et comme un 'input' donc il créer directement l'input email
                
            // On instancie 'RepeatedType' pour comparer les 2 mot de passe qui ajoutera notre "Confirmer mot de passe"              
            ->add('password',RepeatedType::class,[

                // On lui dit quel type de password avec 'PasswordType' 
                'type'=> PasswordType::class,
                'constraints'=> new Length([
                    'min' => 8,
                    'max' => 150
                ]),
                
                'invalid_message' =>'Les mots de passe ne correspond pas ...',
                // On dit que ce champ et requis 
                'required' => true,
                // On ajoute notre label 
                'first_options'=> 
                [
                    "label" => "Mot de passe ",
                    'attr' => [
                        'placeholder' => 'Entrez un mot de passe *'
                    ]
                ],
                
                'second_options'=> [

                    'label' => 'Confirmer votre  mot de passe',
                    'attr' => [
                        'placeholder' => 'Confirmer votre mot de passe *'
                    ]
            
                    ]
            ])
            
            //Ajout du bouton d'envoie 
            ->add('submit', SubmitType::class,[
                'label'=> "S'inscrire",
                'attr' => [
                    'class'=>"bouton-custom btn btn-outline-danger",
                    'path'=>'app_login'
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
