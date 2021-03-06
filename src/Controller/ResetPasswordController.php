<?php

namespace App\Controller;

use App\Classe\Mail;
use App\Entity\ResetPassword;
use App\Entity\User;
use App\Form\ResetPasswordType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class ResetPasswordController extends AbstractController
{
    private $entityManager; 
    public function __construct(EntityManagerInterface $entityManager){
        $this->entityManager = $entityManager;
    }

    // SECTION MOT DE PASSE OUBLIER

    #[Route('/mot-de-passe-oublier', name: 'reset_password')]
    
    public function index(Request $request): Response
    {

        // Si mon utilisateur et connecter 
        if ($this->getUser()) {
           // Je le redirige vers la route 
           return $this -> redirectToRoute('home');
        }
        //On recupere l'email Si il est rentrer 
        if ($request->get('email')) {
            // On vérifie si l'émail existe
            $user = $this->entityManager->getRepository(User::class)->findOneByEmail($request->get('email'));
            if ($user) {
                // Etape 1 : Enregistrer en base la demande de 'reset_password' avec user,token,createdAt
               $reset_password = new ResetPassword();
               $reset_password->setUser($user);
               $reset_password -> setToken(uniqid());
               $reset_password -> setCreatedAt(new \DateTimeImmutable());
               $this->entityManager->persist($reset_password);
               $this->entityManager->flush();

               // Etape 2 :Envoyer un email à l'utilisateur avec un lien lui permettant de mettre a jour sont mot de passe
                $url = $this->generateUrl('update_password',[
                    "token" => $reset_password -> getToken(),
                ]);
               

               $content = "Bonjour ".$user->getFirstname().'<br>'.'Vous avez demander à reinitialiser votre mot de passe sur le site de titi'."<br/> <br/>";
               $content .= "Merci de bien vouloir cliquer sur le lien suivant <a href='".$url."'>mettre à jour mon mot de passe </a>";

               $mail = new Mail(); 
               $mail -> send($user->getEmail(),$user -> getFirstname()."".$user->getLastname(),'Réinitialiser mon mot de passe sur le site de titi ? ',$content);
               $this -> addFlash('notice',"Un email vous était envoyer à l'adresse :"." ".$user->getEmail());
            }else{
                $this -> addFlash('notice','Addresse email  inconnue veuillez resaisir votre email');
            }
        }

        return $this->render('reset_password/index.html.twig');
    }
     // SECTION MOT DE PASSE OUBLIER

     #[Route('/mot-de-passe-oublier/{token}', name: 'update_password')]
    
     public function update(Request $request,$token,UserPasswordHasherInterface $encoder): Response
     {
       $reset_password = $this->entityManager->getRepository(ResetPassword::class)->findOneByToken($token);
       if (!$reset_password) {
           return $this->redirectToRoute('reset_password');
       }
       //Vérifier si le createdAt = new-3
       $now = new \DateTime();
       if ($now > $reset_password->getCreatedAt()->modify('+ 3 hour '))
       {
           // modifier le mot de passe
           $this->addFlash('notice','Votre demande de mot de passe a expiré . Merci de renouveller votre demande .');
           return $this->redirectToRoute('reset_password');

       }
       // Rendre une vue avec mot de passe et confirmez votre mot de passe
       $form = $this->createForm(ResetPasswordType::class);
       $form->handleRequest($request);
       if ($form->isSubmitted()&& $form->isValid()) {
           # code...
           //On recupere de nos input mdp
           $new_pwd = $form->get('new_password')->getData();
           

        // encodage des mot de passes

           // On le hash 
           $password = $encoder->hashPassword($reset_password->getUser(),$new_pwd);
           // Et on envoie dans la bdd
           $reset_password->getUser()->setPassword($password);         
        // Flsuh en base de données 
        $this->entityManager->flush();
        // Redirection de l'utilisateur vers la page de connexion 
        $this->addFlash('notice','Votre mot de passe a bien été mis a jour .');
        return $this->redirectToRoute('app_login');
       }
       return $this->render('reset_password/update.html.twig',[
           "form"=>$form->createView()
       ]);
      
      
     }
}
 