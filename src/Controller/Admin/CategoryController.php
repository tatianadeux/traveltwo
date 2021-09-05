<?php


namespace App\Controller\Admin;



use App\Entity\Category;
use App\Form\CategoryType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class CategoryController extends AbstractController
{
    /**
     * @Route("/admin/categories", name="admin_categories_list")
     */
    public function listCategoryAdmin(CategoryRepository $categoryRepository)
    {
        /* utilisation de l'autowire de Symfony pour instancier une classe */

        $categories = $categoryRepository->findAll();
        /* utilisation d'une méthode prise dans l'Article Repository*/

        return $this->render('admin/list/categories.html.twig', [
            'categories' => $categories
        ]);
    }

    /**
     * @Route("/admin/categories/add", name="admin_categories_add")
     */
    public function addCategoryAdmin(
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManager,
        Request $request)
    {
        /* création de l'instance d'un nouvel article*/
        $category = new Category();

        /* récupération de la méthode createForm de l'Abstract Controller,
        createFrom génère un formulaire avec le gabarit et la variable que l'on veut ajouter */
        $categoryForm = $this->createForm(CategoryType::class, $category);

        /* liaison du formulaire aux données */
        $categoryForm->handleRequest($request);

        /* vérification du formulaire */
        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {

            /* stocke en session un message flash qui sera affiché sur la page suivante */
            $this->addFlash(
                'success',
                'L\'article ' . $category->getName() . ' a bien été ajouté !'
            );
            /* pré-enregistrement des données et envoi en bdd */
            $entityManager->persist($category);
            $entityManager->flush();

            /* à quelle front on renvoie après l'ajout en bdd */
            return $this->redirectToRoute('admin_categories_list');
        }

        return $this->render('admin/add/category_add.html.twig', [
            'categoryForm' => $categoryForm->createView()
        ]); /* ici on crée la vue du formulaire pour préparer le html */
    }

    /**
     * @Route("/admin/categories/{id}", name="admin_categories_read", requirements={"id"="\d+"})
     */
    public function readCategoryAdmin($id, CategoryRepository $categoryRepository)
    {
        $category = $categoryRepository->find($id);

        if (isset($category)){
            return $this->render('admin/show/category_show.html.twig', [
                'category'=>$category
            ]);
        } else {
            throw new NotFoundHttpException('Erreur : Page non trouvée');
        }
    }

    /**
     * @Route("/admin/categories/update/{id}", name="admin_categories_update", requirements={"id"="\d+"})
     */
    public function updateCategoryAdmin(
        $id,
        CategoryRepository $categoryRepository,
        Request $request,
        EntityManagerInterface $entityManager)
    {
        $category = $categoryRepository->find($id);
        $categoryForm = $this->createForm(CategoryType::class, $category);
        $categoryForm->handleRequest($request);

        if ($categoryForm->isSubmitted() && $categoryForm->isValid()) {
            $this->addFlash(
                'success',
                'L\'article ' . $category->getName() . ' a bien été modifié !'
            );
            $entityManager->persist($category);
            $entityManager->flush();

            return $this->redirectToRoute('admin_categories_list');
        }
        return $this->render('admin/add/category_add.html.twig', [
            'categoryForm' => $categoryForm->createView()
        ]);
    }

    /**
     * @Route("/admin/categories/delete/{id}", name="admin_categories_delete", requirements={"id"="\d+"})
     */
    public function deleteCategoryAdmin(
        $id,
        CategoryRepository $categoryRepository,
        EntityManagerInterface $entityManager)
    {
        $category = $categoryRepository->find($id);

        $entityManager->remove($category);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'L\'article ' . $category->getName() . ' a bien été supprimé !'
        );

        return $this->redirectToRoute('admin_categories_list');
    }

}