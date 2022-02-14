<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\Contact;
use App\Form\ContactType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ContactController extends AbstractController
{



    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    
    
    
    #[Route('/nous-contacter', name: 'contact')] 

    public function index(Request $request): Response
    {   
        //On instancie notre class entity  Contact
        $saisie_contact = new Contact();

        $form = $this->createForm(ContactType::class,$saisie_contact); 
        $form->handleRequest($request);

        
        if ($form -> isSubmitted() && $form -> isValid()) {
            
            // Recuperation des données dans le formulaire 
            $saisie_contact = $form->getData();
            // On insert dans notre BDD    
            $this -> entityManager -> persist($saisie_contact);
            $this -> entityManager -> flush();
            //Envoie de mail 
            $mail = new Mail();
            $content = "Bonjour ".$saisie_contact->getFirstname()."<br/>E-commerce vous remercie pour votre contact nous ne manquerons pas de vous répondre  ! ";
            $mail->send($saisie_contact->getEmail(), $saisie_contact->getFirstname(),"Votre message sur le site E-Commerce-Titi",$content);           
            
            $this->addFlash('notice','Merci de nous avoir contactez notre équipe va vous répondre dans les meilleurs délais');            
        }

        return $this->render('contact/index.html.twig',[
            "form"=> $form->createView()
        ]);
    }
}
