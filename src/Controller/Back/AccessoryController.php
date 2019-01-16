<?php

namespace App\Controller\Back;

use App\Entity\Accessory;
use App\Form\AccessoryType;
use App\Repository\AccessoryRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/accessory")
 */
class AccessoryController extends AbstractController
{
    /**
     * @Route("/", name="accessory_index", methods={"GET"})
     */
    public function index(AccessoryRepository $accessoryRepository): Response
    {
        return $this->render('Back/accessory/index.html.twig', ['accessories' => $accessoryRepository->findAll()]);
    }

    /**
     * @Route("/new", name="accessory_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $accessory = new Accessory();
        $form = $this->createForm(AccessoryType::class, $accessory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($accessory);
            $entityManager->flush();

            return $this->redirectToRoute('accessory_index');
        }

        return $this->render('Back/accessory/new.html.twig', [
            'accessory' => $accessory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="accessory_show", methods={"GET"})
     * @param Accessory $accessory
     *
     * @return Response
     */
    public function show(Accessory $accessory): Response
    {
        return $this->render('Back/accessory/show.html.twig', ['accessory' => $accessory]);
    }

    /**
     * @Route("/{id}/edit", name="accessory_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Accessory $accessory): Response
    {
        $form = $this->createForm(AccessoryType::class, $accessory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('accessory_index', ['id' => $accessory->getId()]);
        }

        return $this->render('Back/accessory/edit.html.twig', [
            'accessory' => $accessory,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="accessory_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Accessory $accessory): Response
    {
        if ($this->isCsrfTokenValid('delete'.$accessory->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($accessory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('accessory_index');
    }
}
