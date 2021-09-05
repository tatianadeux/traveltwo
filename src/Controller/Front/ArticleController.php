<?php


namespace App\Controller\Front;


use App\Repository\ArticleRepository;
use App\Repository\FilterRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class ArticleController extends AbstractController
{
    /**
     * @Route("/destination/{id}", name="resultat_show")
     */
    public function resultatShow
    ($id,
     ArticleRepository $articleRepository,
     FilterRepository $filterRepository){
        $climats = $filterRepository->findBy(['category' => 2]);
        $activities = $filterRepository->findBy(['category' => 1]);
        $resultat = $articleRepository->find($id);

        if (isset($resultat)) {
            return $this->render('front/show/article.html.twig', [
                'resultat'=>$resultat,
                'climats'=>$climats,
                'activities'=>$activities
            ]);
        } else {
            throw new NotFoundHttpException('Erreur 418 : Je suis une théière');
        }
    }

}