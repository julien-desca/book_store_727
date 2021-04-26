<?php

namespace App\Controller;

use App\Entity\Book;
use App\Form\BookType;
use App\Repository\BookRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

class BookController extends AbstractController{


    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var BookRepository
     */
    private $bookRepository;

    public function __construct(EntityManagerInterface $manager, BookRepository $bookRepository)
    {
        $this->manager = $manager;
        $this->bookRepository = $bookRepository;
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

    /**
     * @Route("/books", name="list_book")
     */
    public function listBook(Request $request){
        $bookList = $this->bookRepository->findAll();
        return $this->render("books/list.html.twig", ['bookList' => $bookList]);
    }

    /**
     * @Route("/books/{id}", name="detail_book", requirements={"id"="\d+"})
     */
    public function detailBook(Request $request, int $id){
        $book = $this->bookRepository->find($id);
        if($book == null){
            throw new HttpException(404);
        }
        return $this->render("books/detail.html.twig", ['book' => $book]);
    }
}