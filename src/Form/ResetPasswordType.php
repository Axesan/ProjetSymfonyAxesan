<?php

namespace App\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class ResetPasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
           
            ->add('new_password',RepeatedType::class,[

                // On lui dit quel type de password avec 'PasswordType' 
                'type'=> PasswordType::class,
                'constraints'=> new Length([
                    'min' => 8,
                    'max' => 150
                ]),
               
                'invalid_message' =>'Les 2 mots de passe ne correspond pas ...',
                // On dit que ce champ et requis 
                'required' => true,
                // On ajoute notre label 
                'first_options'=> 
                [
                    "label" => "Mon nouveau mot de passe ",
                    'attr' => [
                        'placeholder' => 'Entrez un mot de passe'
                    ]
                ],
                
                'second_options'=> [

                    'label' => 'Confirmer votre nouveau mot de passe',
                    'attr' => [
                        'placeholder' => 'Mettre à votre mot de passe '
                    ]
            
                    ]
            ])
              //Ajout du bouton d'envoie 
            ->add('submit', SubmitType::class,[
                'label'=> "Modifier le mot de passe",
                "attr" => [
                            "class"=>"btn btn-danger"
                    
                    ]
            ]) 
        ;
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
        ]);
    }
}
