<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\OrderDetails;
use App\Form\OrderType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use  Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints\DateTime;

class OrderController extends AbstractController
{


    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager){
    
        $this-> entityManager = $entityManager;
        
    }

    #[Route('/commande', name: 'order')]

    public function index(Cart $cart): Response {  
        
    $form = $this -> createForm(OrderType::class,null,[
            'user' => $this -> getUser()
    ]);

    

    //On affiche les adresses de l'utilisateur seulement de l'utilisateur qui et connecter     
    if (!$this -> getUser() -> getAdresses() -> getValues()) {
        return $this -> redirectToRoute('account_address_add');
    }
 

    return $this->render('order/index.html.twig', [
        "form" => $form->createView(),
        "cart" => $cart->getAll()
    ]);
    
    }


    //Route apres 'Payer ma commande'
    #[Route('/commande/recapitulatif', name: 'order_recap',methods:'POST')]

    public function add(Cart $cart, Request $request): Response {  
        
    $form = $this -> createForm(OrderType::class,null,[
            'user' => $this -> getUser()
    ]);

    

    
    $form -> handleRequest($request);
    //Si lef ormulaire et submit et valide 
    if ($form->isSubmitted() && $form->isValid()) {
            //Date 
            $date = new \DateTimeImmutable();
            $carriers = $form->get('carriers') -> getData();
            //Recuperation de ladresse de livraison 
            $delivery = $form->get('addresses') -> getData();
            $delivery_content = $delivery->getFirstname().''.$delivery->getLastname();
            $delivery_content .= "<br/>".$delivery->getPhone();

            if ( $delivery->getCompany()) {
    
                $delivery_content .= "<br/>".$delivery->getCompany();
             }
            $delivery_content .= "<br/>".$delivery->getAddress();
            $delivery_content .= "<br/>".$delivery->getPostal().''.$delivery->getCity();
            $delivery_content .= "<br/>".$delivery->getCountry();
 
            //Enregistrer ma commande avec l'entity Order()

            $order = new Order();
            $reference =  $date -> format("dmy").'-'.uniqid();
            $order -> setReference($reference);
            $order -> setUser( $this->getUser());
            $order -> setCreatedAt($date);
            $order -> setCarrierName($carriers->getName());
            $order -> setCarrierPrice($carriers->getPrice());
            $order -> setDelivery($delivery_content);
            // Non valider commande non valider
            $order -> setState(0);

            //On enregistre le tout dans la BDD Grâce a notre constructeur plus haut 
            $this->entityManager->persist($order);
            // On initialise une variables qui contien un tableau qu'on va réutiliser dans notre boucle     
         

            // Enregistrer les produits OrderDetail()
            foreach ($cart->getAll() as $product) {

                $orderDetails = new OrderDetails();
                $orderDetails -> setMyOrder ($order);
                $orderDetails -> setProduct ($product["product"]-> getName());
                $orderDetails -> setQuantity ($product['quantity']);
                $orderDetails -> setPrice ($product['product']->getPrice());
                $orderDetails -> setTotal($product['product']->getPrice() * $product['quantity']);
                $this->entityManager->persist($orderDetails); 

            }
         
            //ET ON ENVOIE DANS LA BDD 
             $this ->entityManager->flush();
            
             //Mis en place de ma cle d'api pour stripe
 
             
            return $this->render('order/add.html.twig', [
            
                "cart" => $cart -> getAll(),
                "carrier" => $carriers,
                "delivery" => $delivery,
                "reference" => $order->getReference()
               
            ]);       
    }// EO IF

    return $this->redirectToRoute('cart');
    }   // EO Function add()


}
 