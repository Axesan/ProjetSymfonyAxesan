<?php

namespace App\Controller\Admin;

use App\Entity\Header;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextField;
use EasyCorp\Bundle\EasyAdminBundle\Field\ImageField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextareaField;
use EasyCorp\Bundle\EasyAdminBundle\Field\TextEditorField;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class HeaderCrudController extends AbstractCrudController
{
    public static function getEntityFqcn(): string
    {
        return Header::class;
    }

  
    public function configureFields(string $pageName): iterable
    {
        return [
            
            TextField::new('title',"Titre"),
            TextareaField::new('content'),
            
            TextField::new('btnTitle','Titre de notre bouton'),
            TextField::new('btnUrl','Url du bouton'),
            ImageField::new('illustration')
            // Pour afficher notre image sur notre site 
            ->setBasePath('/assets/imageHeader')
             // Ou doit etre placer la photos une fois uploader ? Dans le dossier "public/assets/ImageProduct"
            ->setUploadDir('public/assets/imageHeader')
 
            ->setUploadedFileNamePattern('[randomhash].[extension]')
            ->setRequired(false),
        ];
    }
   
}
