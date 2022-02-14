<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Category;
use App\Entity\Product;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class CartController extends AbstractController
{
    private $entityManager; 

    public function __construct(EntityManagerInterface $entityManager )
    {
            $this->entityManager = $entityManager;
    }   


    //ROUTE PANIER
     #[Route('/mon-panier', name: 'cart')]
 
    public function index(Cart $cart): Response
    { 
    
        $products = $this -> entityManager -> getRepository(Product::class) -> findByisBest(1);
        $categorie = $this -> entityManager -> getRepository(Category::class) -> findAll();

        return $this -> render('cart/index.html.twig',[
            'cart' => $cart -> getAll(),
            'products' => $products,
            'categorie'=> $categorie
        ]);
    }



    // Route ajouter au panier on met en place une variable 'id' dans l'url pour selectionner l'id du produit 
    #[Route('/cart/add/{id}', name: 'add_to_cart')]
 
    public function add(Cart $cart , $id): Response
    {   
        $cart -> add($id);
       
        
        return $this->redirectToRoute('cart');
    }
    //Option remove tout le panier panier 
    #[Route('/cart/remove/', name: 'remove_my_cart')]
 
    public function remove(Cart $cart): Response
    {   
        $cart -> remove();
        
        return $this->redirectToRoute('products');
    }


     //Option remove d'un article du panier 
     #[Route('/cart/delete/{id}', name: 'delete_to_cart')]
 
     public function delete(Cart $cart,$id): Response
     {   
         $cart -> delete($id);
         
         return $this->redirectToRoute('cart');
     } 

        // Retirer la quantité d'un produit x2 ou x3  
        #[Route('/cart/decrease/{id}', name: 'decrease_to_cart')]
 
        public function decrease(Cart $cart,$id): Response
        {   
            $cart -> decrease($id);
            
            return $this->redirectToRoute('cart');
        }










    }
