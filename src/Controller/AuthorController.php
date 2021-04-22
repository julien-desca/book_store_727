<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController{

    /**
     * @Route("/authors/create", name="create_author")
     */
    public function createAuthor(Request $request){
        
    }
}