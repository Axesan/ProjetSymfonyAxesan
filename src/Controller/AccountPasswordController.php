<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AccountPasswordController extends AbstractController
{   

    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }
    #[Route('/compte/modifier-mon-mot-de-passe', name: 'account_password')]

    public function index(Request $request, UserPasswordHasherInterface $encoder): Response
    {   
        
        //On initialise une variables 'notification' 
        $notification = null;

        $user = $this -> getUser(); 
        $form = $this ->  createForm(ChangePasswordType::class, $user);
        $form ->handleRequest($request);

        // Si le formualaire et submit et valid alors :
        if ($form->isSubmitted() && $form->isValid()) {
            $old_pwd = $form->get('old_password')->getData();
            //Tu verifie si le mot de passe correspond s'il il correspond alors : 
            if ($encoder->isPasswordValid($user,$old_pwd)) {
                //On recupere le nouveau mot de passe
                $new_pwd =$form->get('new_password')->getData();
                // On le hash 
                $password = $encoder->hashPassword($user,$new_pwd);
                // Et on envoie dans la bdd
                $user->setPassword($password);
                $this->entityManager->flush();
                // Si tous c'est bien passer tu le dis a l'utilisateur
                $notification= "Votre mot de passe a bien était mis a jour ";
                

            } else {
                // Sinon tu lui dit que c'est pas bon
                $notification ="Votre mot de passe actuel n'est pas bon";
            }
        }
        
        return $this->render('account/password.html.twig', [
            'form' => $form-> createView(),
            // On fait passer notre variables notification dans notre vue 
            'notification' => $notification
        ]);
    }
}
