<?php


namespace App\Controller;


use App\Repository\ArticleRepository;
use App\Repository\FilterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class PageController extends AbstractController
{
    /**
     * @Route("/", name="home_page")
     */
    public function home(FilterRepository $filterRepository) {
        $climats = $filterRepository->findBy(['category' => 2]);
        $activities = $filterRepository->findBy(['category' => 1]);
        $resultats = [];

        return $this->render('front/home.html.twig', [
            'resultats'=>$resultats,
            'climats'=> $climats,
            'activities'=>$activities
        ]);
    }

    /**
     * @Route("/agence", name="agence_page")
     */
    public function agence() {
        return $this->render('front/agence.html.twig');
    }

    /**
     * @Route("/blog", name="blog_page")
     */
    public function randomBlog (
        ArticleRepository $articleRepository,
        Request $request){

        /*$articles = $articleRepository->findAll();
        dump($articles); die;

        $random = $request->query->get('random');*/



        return $this->render('front/blog.html.twig');
    }

    /**
     * @Route("/contact", name="contact_page")
     */
    public function contact (){
        return $this->render('front/contact.html.twig');
    }

    /**
     * @Route("/mentions-legales", name="legal_page")
     */
    public function legal(){
        return $this->render('front/legal.html.twig');
    }

    /* AXE D'AMELIORATION : que les id dans l'url ne s'affichent plus mais à la place les noms des paramètres */

    /**
     * @Route("/search", name="searchBy")
     */
    public function searchBy(
        ArticleRepository $articleRepository,
        FilterRepository $filterRepository,
        Request $request)
    {
        $climats = $filterRepository->findBy(['category' => 2]);
        $activities = $filterRepository->findBy(['category' => 1]);

        /* création d'une variable qui récupère ce que l'utilisateur à sélectionné */
        $selectionActivity = $request->query->get('ac');
        $selectionClimat = $request->query->get('cl');

        /* récupération des données dans le searchBy et enregistrement dans une variable */
        $resultats = $articleRepository->searchBySelection($selectionActivity, $selectionClimat);

        return $this->render('front/home.html.twig', [
            'climats' => $climats,
            'activities' => $activities,
            'resultats' => $resultats,
        ]);
    }
}