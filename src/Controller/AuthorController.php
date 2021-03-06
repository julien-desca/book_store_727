<?php

namespace App\Controller;

use App\Entity\Author;
use App\Form\AuthorType;
use App\Repository\AuthorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpKernel\Exception\HttpException;

class AuthorController extends AbstractController{

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var AuthorRepository
     */
    private $authorRepository;

    public function __construct(EntityManagerInterface $manager, AuthorRepository $authorRepository){
        $this->manager = $manager;
        $this->authorRepository = $authorRepository;
    }

    /**
     * @Route("/authors/create", name="create_author")
     * @IsGranted("ROLE_ADMIN")
     */
    public function createAuthor(Request $request){
        $author = new Author();

        $form = $this->createForm(AuthorType::class, $author);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->manager->persist($author);
            $this->manager->flush();   
            return $this->redirectToRoute('list_author');
        }

        return $this->render('authors/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/authors", name="list_author")
     */
    public function listAuthor(Request $request){
        $authorList = $this->authorRepository->findAll();
        return $this->render('authors/list.html.twig', ['authorList' => $authorList]);
    }

    /**
     * @Route("/authors/{id}", name="detail_author", requirements={"id"="\d+"})
     */
    public function detailAuthor(Request $request, int $id){
        $author = $this->authorRepository->find($id);
        if($author == null){
            throw new HttpException(404);
        }

        return $this->render("authors/detail.html.twig", ['author' => $author]);
    }
}