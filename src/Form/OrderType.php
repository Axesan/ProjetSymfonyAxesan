<?php

namespace App\Form;

use App\Entity\Adress;
use App\Entity\Carrier;
use App\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class OrderType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $user = $options['user'];
        //Forumaire d'adresse de l'utilisateur 
        $builder
            ->add('addresses',EntityType::class,[
                    'label'=>'Adresses actuel',
                    'required'=>true,
                    'class'=> Adress::class,
                    // On affiche les addresse de mon utilisateur seulement grâce a la jointure faite
                    'choices' => $user->getAdresses(), 
                    'multiple'=>false,
                    'expanded'=> true
                    
            ]) -> add('carriers',EntityType::class,[

                'label'=>'Choisir mon transporteur',
                'required'=>true,
                'class'=> Carrier::class,
                'multiple'=>false,
                'expanded'=> true
                
        ])->add('submit',SubmitType::class,[
            'label' => "Valider ma commande",
            'attr' => [
                'class' => 'btn btn-success btn-lg'
            ]
            ]);
        
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults([
            // Configure your form options here
            'user'=>array()
        ]);
    }
}
