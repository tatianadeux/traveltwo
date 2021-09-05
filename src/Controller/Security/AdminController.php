<?php


namespace App\Controller\Security;


use App\Repository\ArticleRepository;
use App\Repository\CategoryRepository;
use App\Repository\FilterRepository;
use App\Repository\MediaRepository;
use App\Repository\TypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    /**
     * @Route("/admin/home", name="admin_home")
     */
    public function homeAdmin(ArticleRepository $articleRepository,
                              TypeRepository $typeRepository,
                              FilterRepository $filterRepository,
                              CategoryRepository $categoryRepository,
                              MediaRepository $mediaRepository){

        /* affichage des 5 derniers articles-catégories-tags écrits
            recherche par id et du plus récent au plus ancien avec une limite de 5 */
        $lastArticles = $articleRepository->findBy([], ['id'=> 'DESC'], 5);
        $lastTypes = $typeRepository->findBy([],['id'=>'DESC'],5);
        $lastFilters = $filterRepository->findBy([], ['id'=>'DESC'], 5);
        $lastCategories = $categoryRepository->findBy([],['id'=>'DESC'],5);
        $lastMedias = $mediaRepository->findBy([], ['id'=>'DESC'], 5);

        return $this->render('admin/admin_home.html.twig', [
            'articles' => $lastArticles,
            'types' => $lastTypes,
            'filters' => $lastFilters,
            'categories' => $lastCategories,
            'medias'=>$lastMedias
        ]);
    }

}