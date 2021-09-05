<?php


namespace App\Controller\Admin;


use App\Entity\Media;
use App\Form\MediaType;
use App\Repository\MediaRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\String\Slugger\SluggerInterface;

class MediaController extends AbstractController
{
    /**
     * @Route("/admin/medias", name="admin_medias_list")
     */
    public function listMediaAdmin(MediaRepository $mediaRepository)
    {
        /* utilisation de l'autowire de Symfony pour instancier une classe */

        $medias = $mediaRepository->findAll();
        /* utilisation d'une méthode prise dans l'Article Repository*/

        return $this->render('admin/list/medias.html.twig', [
            'medias' => $medias
        ]);
    }

    /**
     * @Route("/admin/medias/add", name="admin_medias_add")
     */
    public function addMediaAdmin(
        MediaRepository $mediaRepository,
        EntityManagerInterface $entityManager,
        Request $request,
        SluggerInterface $slugger)
    {
        /* création de l'instance d'un nouvel article*/
        $media = new Media();

        /* récupération de la méthode createForm de l'Abstract Controller,
        createFrom génère un formulaire avec le gabarit et la variable que l'on veut ajouter */
        $mediaForm = $this->createForm(MediaType::class, $media);

        /* liaison du formulaire aux données */
        $mediaForm->handleRequest($request);

        /* vérification du formulaire */
        if ($mediaForm->isSubmitted() && $mediaForm->isValid()) {

            /*récupération de l'image uploadée par l'utilisateur dans le formulaire*/
            $imageFile = $mediaForm->get('image')->getData();

            /* création d'une condition car l'image n'est pas obligatoire */
            if ($imageFile){
                /* informations sur le chemin de l'image*/
                $originalFileName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                /* suppression des caractères spéciaux*/
                $safeFileName = $slugger->slug($originalFileName);
                /* création du nouveau nom pour l'image avec un identifiant unique*/
                $newFileName = $safeFileName.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('image_directory'),
                        $newFileName
                    );
                } catch (FileException $exception) {
                    return new Response("L'image n'a pas pu être chargée");
                }

                $media->setImage($newFileName);
            }

            /* stocke en session un message flash qui sera affiché sur la page suivante */
            $this->addFlash(
                'success',
                'L\'article ' . $media->getTitle() . ' a bien été ajouté !'
            );
            /* pré-enregistrement des données et envoi en bdd */
            $entityManager->persist($media);
            $entityManager->flush();

            /* à quelle front on renvoie après l'ajout en bdd */
            return $this->redirectToRoute('admin_medias_list');
        }

        return $this->render('admin/add/media_add.html.twig', [
            'mediaForm' => $mediaForm->createView()
        ]); /* ici on crée la vue du formulaire pour préparer le html */
    }

    /**
     * @Route("/admin/medias/update/{id}", name="admin_medias_update", requirements={"id"="\d+"})
     */
    public function updateMediaAdmin(
        $id,
        MediaRepository $mediaRepository,
        Request $request,
        EntityManagerInterface $entityManager,
        SluggerInterface $slugger)
    {
        $media = $mediaRepository->find($id);
        $mediaForm = $this->createForm(MediaType::class, $media);
        $mediaForm->handleRequest($request);

        if ($mediaForm->isSubmitted() && $mediaForm->isValid()) {
            /*récupération de l'image uploadée par l'utilisateur dans le formulaire*/
            $imageFile = $mediaForm->get('image')->getData();

            /* création d'une condition car l'image n'est pas obligatoire */
            if ($imageFile){
                /* informations sur le chemin de l'image*/
                $originalFileName = pathinfo($imageFile->getClientOriginalName(), PATHINFO_FILENAME);
                /* suppression des caractères spéciaux*/
                $safeFileName = $slugger->slug($originalFileName);
                /* création du nouveau nom pour l'image avec un identifiant unique*/
                $newFileName = $safeFileName.'-'.uniqid().'.'.$imageFile->guessExtension();

                try {
                    $imageFile->move(
                        $this->getParameter('image_directory'),
                        $newFileName
                    );
                } catch (FileException $exception) {
                    return new Response("L'image n'a pas pu être chargée");
                }

                $media->setImage($newFileName);
            }

            $this->addFlash(
                'success',
                'L\'article ' . $media->getTitle() . ' a bien été modifié !'
            );
            $entityManager->persist($media);
            $entityManager->flush();

            return $this->redirectToRoute('admin_medias_list');
        }
        return $this->render('admin/add/media_add.html.twig', [
            'mediaForm' => $mediaForm->createView()
        ]);
    }

    /**
     * @Route("/admin/medias/delete/{id}", name="admin_medias_delete", requirements={"id"="\d+"})
     */
    public function deleteMediaAdmin(
        $id,
        MediaRepository $mediaRepository,
        EntityManagerInterface $entityManager)
    {
        $media = $mediaRepository->find($id);

        $entityManager->remove($media);
        $entityManager->flush();

        $this->addFlash(
            'success',
            'L\'article ' . $media->getTitle() . ' a bien été supprimé !'
        );

        return $this->redirectToRoute('admin_medias_list');
    }

}