<?php

namespace App\Controller\Admin;

use App\Entity\Product;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\AssociationField;
use EasyCorp\Bundle\EasyAdminBundle\Field\BooleanField;
use EasyCorp\Bundle\EasyAdminBundle\Field\SlugField;
use EasyCorp\Bundle\EasyAdminBundle\Field\MoneyField;

class ProductCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Product::class;
    }


    public function configureFields(string $pageName): iterable
    {
       // Quel format seront les input dans easyadmin 
       return[

           TextField::new('name','Nom'),
           SlugField::new('slug')->setTargetFieldName('name'),

           ImageField::new('illustration')
           // Pour afficher notre image sur notre site 
           ->setBasePath('/assets/imageProduct')
            // Ou doit etre placer la photos une fois uploader ? Dans le dossier "public/assets/ImageProduct"
           ->setUploadDir('public/assets/imageProduct')

           ->setUploadedFileNamePattern('[randomhash].[extension]')
           ->setRequired(false),

           
           TextField::new('subtitle','Sous-titre'),
           TextareaField::new('description'),
           BooleanField::new('isBest'),
           MoneyField::new('price','Prix')->setCurrency("EUR"),
           AssociationField::new("category",'Catégorie'),
           


       ];
    }
    
}
