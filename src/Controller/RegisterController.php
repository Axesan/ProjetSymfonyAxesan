<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\User;
use App\Form\RegisterType;
use Doctrine\ORM\EntityManagerInterface;


use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegisterController extends AbstractController
{
    /*
     * 
     * On initialise un constructeur pour l'envoie de nos données dans la BDD . 
     * 
     */

    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    // UserPasswordHasher permet de crypter nos mot de passe saisie par l'utilisateur qu'on stock dans la variables "encoder"
    /**
    * @Route("/inscription",name="register")
    */
    public function index(Request $request,UserPasswordHasherInterface  $encoder ):Response
    {   
        
        //Notification de confirmation d'email 
         $notification =null;
        // On instancie notre class User 
        $user = new User();      


        //On créer notre formulaire
        $form = $this -> createForm(RegisterType::class,$user);

        // Analyse de la requêtes par le formulaire
        $form -> handleRequest($request);
      

        // Condition formulaire d'inscription 
        if($form->isSubmitted() && $form->isValid()){
            // On recupere les données 
            $user = $form -> getData(); 

            //On verifie si l'utilisateur n'est pas dans la base de données 

            $search_email = $this->entityManager->getRepository(User::class) -> findOneByEmail($user->getEmail());
            //Si il et pas enregistrée alors : 
                if (!$search_email) {
                    // On encode le mot de passe grâce a l'instance crée plus haut 'UserPasswordHasherInterface'
                    $password = $encoder -> hashPassword($user,$user->getPassword());
                     
                   // Tu me le reinjecte dans dans l'objet "user"
                   $user->setPassword($password);
                   
                   // On insert dans notre BDD         
                   $this -> entityManager -> persist($user);
                   $this -> entityManager -> flush();

                   //Envoie de mail dés que l'utilisateur c'est inscrit 
                   $mail = new Mail();
                   $content = "Bonjour ".$user->getFirstname()."<br/> Bienvenue sur la première boutique de Axesan vous trouverez des produits inédit ! <br> Merci de votre inscription a notre site !";
                   $mail->send($user->getEmail(),$user->getFirstname(),"Bienvenue sur L'e-commerce de Axesan",$content);
                    
                // $notification ="Votre inscription s'est correctement déroulée .Vous pouvez dés à présent vous connecter a votre compte.";
                   
                  return  $this->redirectToRoute('app_login');         
                } else {
                    
                    
                    $notification = "L'email renseigner existe déjà";
                            
                }


           
          
           

           //Mettre une redirection a la paage de connexion 
        //    return new Response('User ajouté ');
        }
        // On déclare nos variable dans la vue 
        return $this->render('register/index.html.twig', [
            // on affiche le formulaire que l'on a creer 
            'form' => $form->createView(),
            'notification' => $notification
         ]);
    }
}
