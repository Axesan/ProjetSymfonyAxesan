<?php 
namespace App\Classe;

use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class Cart
{   
    private $requestStack; 
    private $entityManager; 

    public function __toString()
    {
        return $this -> requestStack ;
    }

    public function __construct(EntityManagerInterface $entityManager, RequestStack  $requestStack)
    {
        $this -> requestStack  =  $requestStack;  
        $this -> entityManager  =  $entityManager;        
    }
    
    
    public function add($id){
        
        $session = $this -> requestStack -> getSession();
        $cart =  $this -> get('cart',[]);
        //Si j'aajoute au panier
        if (!empty($cart[$id])) {
            // Tu ajout + 1 
            $cart[$id]++;
        }else{
            $cart[$id] = 1 ;
        }    
        
        $session -> set('cart',$cart);
        
        
    }
    public function get(){
        $session = $this->requestStack->getSession();
        $foo = $session->get('cart');
        return $foo;
    }
    //Fonction remove de mon panier
    public function remove(){
        $session = $this->requestStack->getSession();
        $foo = $session->remove('cart');
        return $foo;
    }
    //fonction supprimer un produit du panier

    public function delete($id){
        $session = $this -> requestStack -> getSession();
        $cart =  $this -> get('cart',[]);
        unset($cart[$id]);
        return $session -> set('cart',$cart);
    }
    public function decrease($id){
        $session = $this -> requestStack -> getSession();
        // On verifie si la quantite de notre et = 1
        $cart =  $this -> get('cart',[]);
        if ($cart[$id]>1) {
            # code...
            $cart[$id]--;
        }else {
            //Supprime mon produit
            unset($cart[$id]);
        }
        return $session -> set('cart',$cart);

    }
    public function getAll(){
        $carteComplete = [];
        if ($this->get()) {
            # code...
            foreach($this->get()as $id => $quantity){
                $product_object = $this->entityManager->getRepository(Product::class)->findOneById($id);

                /*
                 * Si dans l'url exemple : http://127.0.0.1:8000/cart/add/{id} l'id ne corespond pas au produit alors tu delete du l'id du produit non enregistrer et tu continue la boucle
                 */
                if (!$product_object){
                    $this -> delete($id);
                    continue;
                }
                    $carteComplete[] = [
                        'product' => $product_object , 
                        'quantity'=>$quantity
                    ];
            }
            
        }
        return $carteComplete;
    }

}
