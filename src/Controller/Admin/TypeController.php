<?php


namespace App\Controller\Admin;


use App\Entity\Type;
use App\Form\TypeType;
use App\Repository\TypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;

class TypeController extends AbstractController
{
    /**
     * @Route("/admin/types", name="admin_types_list")
     */
    public function listTypeAdmin(TypeRepository $typeRepository)
    {
        /* utilisation de l'autowire de Symfony pour instancier une classe */

        $types = $typeRepository->findAll();
        /* utilisation d'une méthode prise dans l'Article Repository*/

        return $this->render('admin/list/types.html.twig', [
            'types' => $types
        ]);
    }

    /**
     * @Route("/admin/types/add", name="admin_types_add")
     */
    public function addTypeAdmin(
        TypeRepository $typeRepository,
        EntityManagerInterface $entityManager,
        Request $request)
    {
        /* création de l'instance d'un nouvel article*/
        $type = new Type();

        /* récupération de la méthode createForm de l'Abstract Controller,
        createFrom génère un formulaire avec le gabarit et la variable que l'on veut ajouter */
        $typeForm = $this->createForm(TypeType::class, $type);

        /* liaison du formulaire aux données */
        $typeForm->handleRequest($request);

        /* vérification du formulaire */
        if ($typeForm->isSubmitted() && $typeForm->isValid()) {

            /* stocke en session un message flash qui sera affiché sur la page suivante */
            $this->addFlash(
                'success',
                'L\'article ' . $type->getName() . ' a bien été ajouté !'
            );
            /* pré-enregistrement des données et envoi en bdd */
            $entityManager->persist($type);
            $entityManager->flush();

            /* à quelle front on renvoie après l'ajout en bdd */
            return $this->redirectToRoute('admin_types_list');
        }

        return $this->render('admin/add/type_add.html.twig', [
            'typeForm' => $typeForm->createView()
        ]); /* ici on crée la vue du formulaire pour préparer le html */
    }

    /**
     * @Route("/admin/types/{id}", name="admin_types_read", requirements={"id"="\d+"})
     */
    public function readTypeAdmin($id, TypeRepository $typeRepository)
    {
        $type = $typeRepository->find($id);

        if (isset($type)){
            return $this->render('admin/show/type_show.html.twig', [
                'type'=>$type
            ]);
        } else {
            throw new NotFoundHttpException('Erreur : Page non trouvée');
        }
    }

    /**
     * @Route("/admin/types/update/{id}", name="admin_types_update", requirements={"id"="\d+"})
     */
    public function updateTypeAdmin(
        $id,
        TypeRepository $typeRepository,
        Request $request,
        EntityManagerInterface $entityManager)
    {
        $type = $typeRepository->find($id);
        $typeForm = $this->createForm(TypeType::class, $type);
        $typeForm->handleRequest($request);

        if ($typeForm->isSubmitted() && $typeForm->isValid()) {
            $this->addFlash(
                'success',
                'L\'article ' . $type->getName() . ' a bien été modifié !'
            );
            $entityManager->persist($type);
            $entityManager->flush();

            return $this->redirectToRoute('admin_types_list');
        }
        return $this->render('admin/add/type_add.html.twig', [
            'typeForm' => $typeForm->createView()
        ]);
    }

    /**
     * @Route("/admin/types/delete/{id}", name="admin_types_delete", requirements={"id"="\d+"})
     */
    public function deleteTypeAdmin(
        $id,
        TypeRepository $typeRepository,
        EntityManagerInterface $entityManager)
    {
        $type = $typeRepository->find($id);
        $entityManager->remove($type);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'Le type d\'article ' . $type->getName() . ' a bien été supprimé !'
        );


        return $this->redirectToRoute('admin_types_list');
    }

}