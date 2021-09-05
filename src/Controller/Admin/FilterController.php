<?php


namespace App\Controller\Admin;


use App\Entity\Filter;
use App\Form\FilterType;
use App\Repository\FilterRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class FilterController extends AbstractController
{
    /**
     * @Route("/admin/filters", name="admin_filters_list")
     */
    public function listFilterAdmin(FilterRepository $filterRepository)
    {
        /* utilisation de l'autowire de Symfony pour instancier une classe */

        $filters = $filterRepository->findAll();
        /* utilisation d'une méthode prise dans l'Article Repository*/

        return $this->render('admin/list/filters.html.twig', [
            'filters' => $filters
        ]);
    }

    /**
     * @Route("/admin/filters/add", name="admin_filters_add")
     */
    public function addFilterAdmin(
        FilterRepository $filterRepository,
        EntityManagerInterface $entityManager,
        Request $request)
    {
        /* création de l'instance d'un nouvel article*/
        $filter = new Filter();

        /* récupération de la méthode createForm de l'Abstract Controller,
        createFrom génère un formulaire avec le gabarit et la variable que l'on veut ajouter */
        $filterForm = $this->createForm(FilterType::class, $filter);

        /* liaison du formulaire aux données */
        $filterForm->handleRequest($request);

        /* vérification du formulaire */
        if ($filterForm->isSubmitted() && $filterForm->isValid()) {

            /* stocke en session un message flash qui sera affiché sur la page suivante */
            $this->addFlash(
                'success',
                'Le filtre "' . $filter->getName() . '" a bien été ajouté !'
            );
            /* pré-enregistrement des données et envoi en bdd */
            $entityManager->persist($filter);
            $entityManager->flush();

            /* à quelle front on renvoie après l'ajout en bdd */
            return $this->redirectToRoute('admin_filters_list');
        }

        return $this->render('admin/add/filter_add.html.twig', [
            'filterForm' => $filterForm->createView()
        ]); /* ici on crée la vue du formulaire pour préparer le html */
    }

    /**
     * @Route("/admin/filters/{id}", name="admin_filters_read", requirements={"id"="\d+"})
     */
    public function readFilterAdmin($id, FilterRepository $filterRepository)
    {
        $filter = $filterRepository->find($id);

        if (isset($filter)){
            return $this->render('admin/show/filter_show.html.twig', [
                'filter'=>$filter
            ]);
        } else {
            throw new NotFoundHttpException('Erreur : Page non trouvée');
        }
    }

    /**
     * @Route("/admin/filters/update/{id}", name="admin_filters_update", requirements={"id"="\d+"})
     */
    public function updateFilterAdmin(
        $id,
        FilterRepository $filterRepository,
        Request $request,
        EntityManagerInterface $entityManager)
    {
        $filter = $filterRepository->find($id);
        $filterForm = $this->createForm(FilterType::class, $filter);
        $filterForm->handleRequest($request);

        if ($filterForm->isSubmitted() && $filterForm->isValid()) {
            $this->addFlash(
                'success',
                'Le filtre "' . $filter->getName() . '" a bien été modifié !'
            );
            $entityManager->persist($filter);
            $entityManager->flush();

            return $this->redirectToRoute('admin_filters_list');
        }
        return $this->render('admin/add/filter_add.html.twig', [
            'filterForm' => $filterForm->createView()
        ]);
    }

    /**
     * @Route("/admin/filters/delete/{id}", name="admin_filters_delete", requirements={"id"="\d+"})
     */
    public function deleteFilterAdmin(
        $id,
        FilterRepository $filterRepository,
        EntityManagerInterface $entityManager)
    {
        $filter = $filterRepository->find($id);

        $entityManager->remove($filter);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Le filtre "' . $filter->getName() . '" a bien été supprimé !'
        );

        return $this->redirectToRoute('admin_filters_list');
    }

}