<?php 
// On intialise notre classe pour filtrer les produits celons leur catégorie
namespace App\Classe;

use App\Entity\Category;
use App\Form\SearchType;

class Search 
{
    /**
     * @var string
     */
    public function __toString()
    {
        return $this-> string ;
    }
    public $string = "" ; 

    /**
     * @var Category[]
     */
    public $categories = [];
}

