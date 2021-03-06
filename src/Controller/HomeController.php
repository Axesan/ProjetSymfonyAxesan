<?php

namespace App\Controller;
use App\Classe\Mail;
use App\Entity\Category;
use App\Entity\Header;
use App\Entity\Product;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class HomeController extends AbstractController
{
    private $entityManager;
    public function __construct(EntityManagerInterface $entityManager) {
                $this-> entityManager = $entityManager;
    }
    /**
     * @Route("/",name="home")
    */
public function index(): Response {  
       
        $products = $this->entityManager->getRepository(Product::class) -> findByisBest(1);
        $categorie = $this->entityManager->getRepository(Category::class) -> findAll();
        $header =$this->entityManager->getRepository(Header::class) -> findAll();
    
        // On passe nos variables dans la vue twig . 
        return $this->render('home/index.html.twig', [
          "products" => $products,
          "categorie" => $categorie,
          "header" =>$header
        ]);
    }
}
