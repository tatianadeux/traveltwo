<?php


namespace App\Controller\Admin;


use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use App\Repository\FilterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/admin/articles", name="admin_articles_list")
     */
    public function listArticleAdmin(ArticleRepository $articleRepository)
    {
        /* utilisation de l'autowire de Symfony pour instancier une classe */

        $articles = $articleRepository->findAll();
        /* utilisation d'une méthode prise dans l'Article Repository*/

        return $this->render('admin/list/articles.html.twig', [
            'articles' => $articles
        ]);
    }

    /**
     * @Route("/admin/articles/add", name="admin_articles_add")
     */
    public function addArticleAdmin(
        ArticleRepository $articleRepository,
        EntityManagerInterface $entityManager,
        Request $request)
    {

        /* création de l'instance d'un nouvel article*/
        $article = new Article();

        /* récupération de la méthode createForm de l'Abstract Controller,
        createFrom génère un formulaire avec le gabarit et la variable que l'on veut ajouter */
        $articleForm = $this->createForm(ArticleType::class, $article);

        /* liaison du formulaire aux données */
        $articleForm->handleRequest($request);

        /* vérification du formulaire */
        if ($articleForm->isSubmitted()) {
            if($articleForm->isValid()) {

              /*  $article->setDate(\DateTime::createFromFormat())*/
                /* pré-enregistrement des données et envoi en bdd */
                $entityManager->persist($article);
                $entityManager->flush();

                /* à quelle front on renvoie après l'ajout en bdd */
                return $this->redirectToRoute('admin_articles_list');
            }
        }

        return $this->render('admin/add/article_add.html.twig', [
            'articleForm' => $articleForm->createView()
        ]);
        /* ici on crée la vue du formulaire pour préparer le html */
    }

    /**
     * @Route("/admin/articles/{id}", name="admin_articles_read", requirements={"id"="\d+"})
     */
    public function readArticleAdmin(
        $id,
        ArticleRepository $articleRepository,
        FilterRepository $filterRepository)
    {
        $climats = $filterRepository->findBy(['category' => 2]);
        $activities = $filterRepository->findBy(['category' => 1]);
        $resultat = $articleRepository->find($id);

        if (isset($resultat)) {
            return $this->render('admin/show/article_show.html.twig', [
                'resultat'=>$resultat,
                'climats'=>$climats,
                'activities'=>$activities
            ]);
        } else {
            throw new NotFoundHttpException('Erreur : Page non trouvée');
        }
    }


    /**
     * @Route("/admin/articles/update/{id}", name="admin_articles_update", requirements={"id"="\d+"})
     */
    public function updateArticleAdmin(
        $id,
        ArticleRepository $articleRepository,
        Request $request,
        EntityManagerInterface $entityManager)
    {
        $article = $articleRepository->find($id);
        $articleForm = $this->createForm(ArticleType::class, $article);
        $articleForm->handleRequest($request);

        if ($articleForm->isSubmitted() && $articleForm->isValid()) {

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('admin_articles_list');
        }
        return $this->render('admin/add/article_add.html.twig', [
            'articleForm' => $articleForm->createView()
        ]);
    }

    /**
     * @Route("/admin/articles/delete/{id}", name="admin_articles_delete", requirements={"id"="\d+"})
     */
    public function deleteArticleAdmin(
        $id,
        ArticleRepository $articleRepository,
        EntityManagerInterface $entityManager)
    {
        $article = $articleRepository->find($id);

        $entityManager->remove($article);
        $entityManager->flush();


        return $this->redirectToRoute('admin_articles_list');
    }

}
