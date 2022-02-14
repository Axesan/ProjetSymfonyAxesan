<?php

namespace App\Controller;

use App\Classe\Cart;
use App\Form\AddressType;
use App\Entity\Adress;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AccountAddressController extends AbstractController

{

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this -> entityManager = $entityManager;
        
    }
    #[Route('/compte/addresses', name: 'account_address')]
    
    public function index(): Response
    {

        return $this->render('account/address.html.twig');
    }
    #[Route('/compte/ajouter-une-adresse', name: 'account_address_add')]
    
    public function add(Cart $cart ,Request $request): Response
    {   
        
        $address = new Adress();
        $form = $this->createForm(AddressType::class,$address);
        $form -> handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()) {
            $address->setUser ( $this->getUser() );

            //Tu prepare avant l'envoie dans la BDD 
            $this -> entityManager -> persist($address);
            $this-> entityManager -> flush();

            // Si je suis dans "panier" alors tu me redirige vers 'order' qui sont mes commandes
            if ($cart->get()) {
                return $this->redirectToRoute('order');
            } else {
                return $this->redirectToRoute('account_address');
            }

           
        }
        return $this->render('account/address_add.html.twig',[
            'form' => $form->createView()
        ]);
    }



    #[Route('/compte/modifier-une-adresse/{id}', name: 'account_address_edit')]
    
    public function edit(Request $request, $id): Response
    {   
        // On verifie l'id a editer
        $address = $this-> entityManager ->getRepository(Adress::class) -> findOneById($id);

        if (!$address || $address->getUser() != $this->getUser() ) {
            return $this -> redirectToRoute('account_address');
        }

        $form = $this->createForm(AddressType::class,$address);
        $form -> handleRequest($request);

        if ($form->isSubmitted()&& $form->isValid()) {
            //Tu prepare avant l'envoie dans la BDD 
            $this-> entityManager -> flush();
            //Si tout et ok tu me redirige 
           return $this->redirectToRoute('account_address');

           
        }
        return $this->render('account/address_add.html.twig',[
            'form' => $form->createView()
        ]);
    }


    #[Route('/compte/delete-une-adresse/{id}', name: 'account_address_delete')]
    
    public function delete($id): Response
    {   
        // On verifie l'id a editer
        $address = $this-> entityManager -> getRepository(Adress::class) -> findOneById($id);

        if (!$address || $address->getUser() == $this->getUser() ) {

            //On supprime de notre base de données 
            $this -> entityManager -> remove($address);
            $this -> entityManager -> flush();
            
        }
        
        return $this -> redirectToRoute('account_address');
        
    }


}
