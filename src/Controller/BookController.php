<?php

namespace App\Controller;

use App\Entity\Book;
use App\Entity\CartLine;
use App\Form\BookType;
use App\Repository\BookRepository;
use App\Repository\CartLineRepository;
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

    /**
     * @var CartLineRepository
     */
    private $cartLineRepository;

    public function __construct(EntityManagerInterface $manager,
                                BookRepository $bookRepository, 
                                CartLineRepository $cartLineRepository)
    {
        $this->manager = $manager;
        $this->bookRepository = $bookRepository;
        $this->cartLineRepository = $cartLineRepository;
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

    /**
     * @Route("/books/{id}/addToCart", name="add_to_cart", requirements={"id"="\d+"})
     */
    public function addBookToCart(Request $request, int $id){
        $book = $this->bookRepository->find($id);
        if($book == null){
            throw new HttpException(404);
        }
        
        $cartLine = $this->cartLineRepository->findByUserAndBook($this->getUser(), $book);
        if($cartLine == null){
            $cartLine = new CartLine();
            $cartLine->setBook($book);
            $cartLine->setQuantity(1);
            $cartLine->setUser($this->getUser());
        }
        else{
            $cartLine->setQuantity($cartLine->getQuantity() + 1);
        }    

        $this->manager->persist($cartLine);
        $this->manager->flush();

        $this->addFlash('notif', 'Votre produit à bien été ajouté au panier');
        return $this->redirectToRoute('detail_book', ['id'=>$id]);
    }
}