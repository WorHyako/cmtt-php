<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;

use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

/**
 * Crud controller to allow to manage table in DB.
 *
 * @since   0.0.1
 *
 * @author  WorHyako
 */
#[Route('/ad/crud')]
class AdCrudController extends AbstractController
{
    /**
     * Root page to show all data.
     *
     * @param AdRepository $adRepository Object repository.
     *
     * @return Response Root page render generator.
     */
    #[Route('/', name: 'ad_crud_index', methods: ['GET'])]
    public function index(AdRepository $adRepository): Response
    {
        return $this->render('ad_crud/index.html.twig', [
            'ads' => $adRepository->findAll(),
        ]);
    }

    /**
     * Creates new empty object and redirect to editing page.
     *
     * @param Request $request Request to form parameters.
     *
     * @param EntityManagerInterface $entityManager Entity manager.
     *
     * @return Response
     */
    #[Route('/new',
        name: 'ad_crud_new',
        methods: ['GET', 'POST'])]
    public function new(Request                $request,
                        EntityManagerInterface $entityManager): Response
    {
        $ad = new Ad();
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($ad);
            $entityManager->flush();

            return $this->redirectToRoute('ad_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ad_crud/new.html.twig', [
            'ad' => $ad,
            'form' => $form,
        ]);
    }

    /**
     * Shows selected object.
     *
     * @param Ad $ad Entity to show.
     *
     * @return Response Show page render generator.
     */
    #[Route('/{id}',
        name: 'ad_crud_show',
        methods: ['GET'])]
    public function show(Ad $ad): Response
    {
        return $this->render('ad_crud/show.html.twig', [
            'ad' => $ad,
        ]);
    }

    /**
     * Edits of deletes selected object.
     *
     * @param Request $request Request.
     *
     * @param Ad $ad Object to edit.
     *
     * @param EntityManagerInterface $entityManager Entity manager.
     *
     * @return Response
     */
    #[Route('/{id}/edit',
        name: 'ad_crud_edit',
        methods: ['GET', 'POST'])]
    public function edit(Request                $request,
                         Ad                     $ad,
                         EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AdType::class, $ad);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('ad_crud_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('ad_crud/edit.html.twig', [
            'ad' => $ad,
            'form' => $form,
        ]);
    }

    /**
     * Deletes selected object.
     *
     * @param Request $request Request.
     *
     * @param Ad $ad Object to delete.
     *
     * @param EntityManagerInterface $entityManager Entity manager.
     *
     * @return Response
     */
    #[Route('/{id}',
        name: 'ad_crud_delete',
        methods: ['POST'])]
    public function delete(Request                $request,
                           Ad                     $ad,
                           EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $ad->getId(), $request->getPayload()->getString('_token'))) {
            $entityManager->remove($ad);
            $entityManager->flush();
        }

        return $this->redirectToRoute('ad_crud_index', [], Response::HTTP_SEE_OTHER);
    }
}
