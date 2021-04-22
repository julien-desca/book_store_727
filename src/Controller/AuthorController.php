<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AuthorController extends AbstractController{

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager){
        $this->manager = $manager;
    }

    /**
     * @Route("/authors/create", name="create_author")
     */
    public function createAuthor(Request $request){
        $author = new Author();

        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->manager->persist($author);
            $this->manager->flush();   
        }

        return $this->render('authors/create.html.twig', ['form' => $form->createView()]);
    }
}