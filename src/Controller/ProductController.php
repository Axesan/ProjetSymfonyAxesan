<?php

namespace App\Controller;

use App\Classe\Search;
use App\Entity\Product;
use App\Form\SearchType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ProductController extends AbstractController
{
    private $entityManager;
    public  function __construct(EntityManagerInterface $entityManager){
        $this->entityManager =$entityManager;
    }
    
    #[Route('/nos-produits', name: 'products')]
    public function index(Request $request): Response
    {
        //On recupere nos produits 
        $product = $this -> entityManager -> getRepository(Product::class)->findAll();

        // Initialisation de ma classe 'search'
        $search = new Search();
        // Mise en place de ma barre de recherche , on créer le formulaire a partir de $search 
        $form = $this->createForm(SearchType::class,$search); 
        // On ecoute la requete du formulaire
        $form->handleRequest($request);
        //Si le formulaire et soumis et valid donc tu fait ta recherche
        if ($form->isSubmitted() && $form->isValid()) {
               $product =$this -> entityManager -> getRepository(Product::class)->findWithSearch($search);
               
        }else{
            //Sinon tu m'affiche tous les produits
            $product =$this -> entityManager -> getRepository(Product::class)->findAll();
               
        }

        
        return $this->render('product/index.html.twig', [
            "products"=>$product ,
            "form" => $form -> createView()

        ]);
    }

    // On met en place la vue des articles grace au slug on aurait pue afficher la vue grace au name ou autres
    //On inject {slug} qui est comme ma variable on pourra donc rentrer notre parametre grace a la variables $slug 

    #[Route('/produit/{slug}', name: 'product')]
    public function show($slug)
    {
        //On recupere nos produits avec le slug
        $product = $this -> entityManager -> getRepository(Product::class)->findOneBySlug($slug);
        //On utilise cette fonctionnalités qui permet de selectionner les produits "phar" de la boutique
        $products = $this->entityManager->getRepository(Product::class) -> findByisBest(1);
         
        // Si le slug ne correspond pas alors tu me redirige vers la page product 
        if(!$product){
            return $this -> redirectToRoute('products');
        } 

        return $this->render('product/show.html.twig', [
            "products"=>$product,  
            "product"=>$products    
        ]);
    }
}