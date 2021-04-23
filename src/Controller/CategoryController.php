<?php

namespace App\Controller;

use App\Entity\Category;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

class CategoryController extends AbstractController{

    /**
     * @var EntityManagerInterface
     */
    private $manager;

    /**
     * @var CategoryRepository
     */
    private $categoryRepository;

    public function __construct(EntityManagerInterface $manager, CategoryRepository $categoryRepository)
    {
        $this->manager = $manager;
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * @Route("/categories/create", name="create_category")
     * @IsGranted("ROLE_ADMIN")
     */
    public function createCategory(Request $request){
        $category = new Category();

        $form = $this->createFormBuilder($category)
                    ->add('name', TextType::class, ['label' => 'Nom de la catégorie'])
                    ->add('save', SubmitType::class, ['label' => 'Sauvegarder'])
                    ->getForm();
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $this->manager->persist($category);
            $this->manager->flush();
            return $this->redirectToRoute('list_category');
        }

        return $this->render('categories/create.html.twig', ['form' => $form->createView()]);
    }

    /**
     * @Route("/categories", name="list_category")
     */
    public function listCategory(Request $request){
        $categoryList = $this->categoryRepository->findAll();
        return $this->render('categories/list.html.twig', ['categoryList' => $categoryList]);
    }
    
}