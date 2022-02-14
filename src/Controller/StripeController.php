<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Entity\Order;
use App\Entity\Product;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

// Dépendance de mon api 'stripe' 
use Stripe\Stripe;
use Stripe\Checkout\Session;
use Symfony\Component\HttpFoundation\JsonResponse;

class StripeController extends AbstractController
{
    #[Route('/commande/create-session/{reference}', name: 'stripe_create_session')]

    public function index(EntityManagerInterface $entityManager, Cart $cart,$reference): Response
    {    
         $YOUR_DOMAIN = 'http://127.0.0.1:8000';
         $product_for_stripe = [];
         $order = $entityManager->getRepository(Order::class)->findOneByReference($reference);



        //Si il trouve pas de order 
        if(!$order){
            new JsonResponse(['error' => 'order' ]);
        }
            foreach ($order->getOrderDetails()->getValues() as $product) {
                  // Configuration de mon API 
               $product_object = $entityManager->getRepository(Product::class)->findOneByName($product->getProduct( ));
                  $product_for_stripe[]=[
                    'price_data' => [
                        'currency' => 'eur',
                        //On affiche le prix a payer
                        'unit_amount' => $product->getPrice(),
                        'product_data'=>[
                            'name'=> $product -> getProduct(),
                            'images'=>[$YOUR_DOMAIN."/assets/imageProduct/".$product_object -> getIllustration()]
                            ]                      
                   ],                
                    'quantity' =>  $product->getQuantity(),                
                ];
            }//Eo foreach
        

            $product_for_stripe[]=[

                'price_data' => [
                    'currency' => 'eur',
                    //On affiche le prix a payer
                    'unit_amount' => $order -> getCarrierPrice(),

                    'product_data' => [
                       'name' => $order -> getCarrierName(),
                       'images' => [$YOUR_DOMAIN]
                        ]                  
               ],
            
                'quantity' =>  1,
            
            ];
       
            
            Stripe::setApiKey('sk_test_51KO0jGKxPRXSB4TZ95ESqBjUDLkBV0EHxyb5txchSNwu6cfJ7tj23NvVQNmGpD43KQw56n7NiTbMd6kqT0XyqeLQ00BBUZHEaK');
               
             $checkout_session =Session::create([
                'customer_email' => $this->getUser()->getEmail(),
               'payment_method_types' => ['card'],             
               'line_items' => [
                $product_for_stripe
               ],             
               'mode' => 'payment',
                //Les domaine pour si l'utilisateur a reussi son payement ou non . 
                 'success_url' => $YOUR_DOMAIN . '/commande/merci/{CHECKOUT_SESSION_ID}',             
                 'cancel_url' => $YOUR_DOMAIN . '/commande/erreur/{CHECKOUT_SESSION_ID}',
             
             ]);
             $order->setStripeSessionId($checkout_session->id);
             $entityManager -> flush();

             $response = new JsonResponse(['id' => $checkout_session -> id ]);
             return $response;
            
    }
}
