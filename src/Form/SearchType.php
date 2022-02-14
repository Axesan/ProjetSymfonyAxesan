<?php 
namespace App\Form;

use App\Classe\Search;
use App\Entity\Category;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SearchType as TypeSearchType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\OptionsResolver\OptionsResolver;

class SearchType extends AbstractType

{   //Formulaire de recherche
    public function buildForm(FormBuilderInterface $builder, array $options){
        $builder
            ->add('string', TextType::class,[
                'label'=> "Barre de recherche",
                'required'=>false,
                'attr' => [
                    'placeholder'=> 'Votre recherche ... ',
                    "class"=> 'form-control-sm border-dark border radius-4'
                ]
            ])
            // On affiche nos catégorie 
            ->add('categories',EntityType::class,[
                'label'=> false,
                'required'=>false,
                'class'=> Category::class,
                'multiple'=>true,
                'expanded'=> true

            ])
            ->add('submit',SubmitType::class,[
                'label'=> false,
                'attr'=> [
                    'class'=>'btn-outline-danger btn-sm rounded-circle bi bi-search bouton-search'
                ]

            ])
            
        ;
    }






    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Search::class,
            "method" => 'GET', 
            'crsf_protection'=> false,

        ]);
    }
    public function getBlockPrefix()
    {
        return ""; 
    }
}
