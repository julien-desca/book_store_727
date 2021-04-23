<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;

class BookController extends AbstractController{


    /**
     * @var EntityManagerInterface
     */
    private $manager;

    public function __construct(EntityManagerInterface $manager)
    {
        $this->manager = $manager;
    }

    /**
     * @Route("/books/create", name="create_book")
     * @IsGranted("ROLE_ADMIN")
     */
    public function createBook(Request $request){
        $book = new Book();
        $form = $this->createForm(BookType::class, $book);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $book->setCreatedAt(new DateTime());
            $this->manager->persist($book);
            $this->manager->flush();
        }

        return $this->render("books/create.html.twig", ["form" => $form->createView()]);
    }
}