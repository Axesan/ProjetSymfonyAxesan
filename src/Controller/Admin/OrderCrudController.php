<?php

namespace App\Controller\Admin;

use App\Classe\Mail;
use App\Entity\Order;
use Doctrine\ORM\EntityManagerInterface;
use phpDocumentor\Reflection\Types\Boolean;
use App\Controller\Admin\OrderCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Config\Crud;
use EasyCorp\Bundle\EasyAdminBundle\Config\Action;
use EasyCorp\Bundle\EasyAdminBundle\Field\IdField;
use EasyCorp\Bundle\EasyAdminBundle\Config\Actions;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ArrayField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ChoiceField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\DateTimeField;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Router\CrudUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class OrderCrudController extends AbstractCrudController
{


    private $entityManager;
    private $crudUrlGenerator;

    public function __construct(EntityManagerInterface $entityManager,AdminUrlGenerator $crudUrlGenerator)
    {

        $this -> entityManager = $entityManager;
        $this -> crudUrlGenerator = $crudUrlGenerator;

    }
    public static function getEntityFqcn(): string
    {
        return Order::class;
    }
    // Fonction 
    public function configureActions(Actions $actions): Actions
    {
        $updatePreparation =Action::new('updatePreparation','Preparation en cours','far fa-clock')->linkToCrudAction('updatePreparation');
        $deliveryPreparation =Action::new('updateDelivery','Livraison en cour','far fa-calendar-check')->linkToCrudAction('updateDelivery');
        $deliverySuccess =Action::new('deliverySuccess','Livraison effectuée','far fa-calendar-check')->linkToCrudAction('deliverySuccess');

        return $actions
        ->add('detail',$updatePreparation)
        ->add('detail',$deliveryPreparation)
        ->add('detail',$deliverySuccess)
        ->add('index','detail');
    }


    
    public function deliverySuccess(AdminContext $context){ 

        $order = $context->getEntity()->getInstance();
        $order->setState(4);
        $this->entityManager->flush();

        $this->addFlash('notice','<span style="color:green"> La commande <strong> N°'.$order->getReference()."</strong> a été livré et notifier a l'utilisateur "."<span/>");
        
        $url = $this->crudUrlGenerator
        ->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        return $this -> redirect($url); 

    }

    public function updateDelivery(AdminContext $context){

            $order = $context->getEntity()->getInstance();
            $order->setState(3);
            $this->entityManager->flush();

            $this->addFlash('notice','<span style="color:orange"> La commande <strong> N° '.$order->getReference().' </strong>et bien en cours de livraison  '.'<span/>');
            
            $url = $this->crudUrlGenerator
            ->setController(OrderCrudController::class)
            ->setAction('index')
            ->generateUrl();

              // Envoyer un email a notre client pour lui confirmer sa commande   
              $mail = new Mail();
              $content = "Bonjour ".$order->getUser()->getFirstname()."<br/>Votre commande et en cours de livraison merci de votre confiance ";
              $mail->send($order->getUser()->getEmail(),$order->getUser()->getFirstname(),"Votre commande a bien etait expédier",$content);

            return $this -> redirect($url); 

    }

    public function updatePreparation(AdminContext $context){ 

        $order = $context->getEntity()->getInstance();
        $order->setState(2);
        $this->entityManager->flush();

        $this->addFlash('notice','<span style="color:green"> La commande <strong> N°'.$order->getReference().'</strong> et bien en cours de préparation '.'<span/>');
        
        $url = $this->crudUrlGenerator
        ->setController(OrderCrudController::class)
        ->setAction('index')
        ->generateUrl();

        return $this -> redirect($url); 

    }

    /**
     * Permet de modifier nos input dans l'administration 
     * 
     */
    public function configureFields(string $pageName): iterable
    {
        return [
            
           IdField::new('id'),
           DateTimeField::new('createdAt','Passée le'),
           TextField::new('user.fullName','Utilisateur'),
           TextEditorField::new('delivery','Adresse de livraison')->onlyOnDetail(),
           TextField::new('carrierName','Transporteur'),
        //    MoneyField::new('total') -> setCurrency('EUR'),
           MoneyField::new('CarrierPrice','Frais de port') -> setCurrency('EUR'),
           //Savoir si le panier a etait payer ou non 
        
           ChoiceField::new('state',"Status de commande")->setChoices([
               'Non payée' => 0,
               "Payée" => 1,
               'Préparation en cours'=>2,
               'Livraison en cours' => 3,
               'Livraison effectuée' => 4

           ]),
           ArrayField::new('orderDetails','Produit acheter')->hideOnIndex(),
           TextField::new('reference','Réferences du panier'),
           
           
           
           

        ];
    }
    public function configureCrud(Crud $crud): Crud
    {
        return $crud->setDefaultSort(["id" => 'DESC']);
    }

   
}
