<?php

namespace App\Controller;

use App\Entity\Steps;
use App\Form\StepsType;
use App\Repository\StepsRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/steps")
 */
class StepsController extends AbstractController
{
    /**
     * @Route("/", name="steps_index", methods={"GET"})
     */
    public function index(StepsRepository $stepsRepository): Response
    {
        return $this->render('steps/index.html.twig', [
            'steps' => $stepsRepository->findAll(),
        ]);
    }

    /**
     * @Route("/new", name="steps_new", methods={"GET","POST"})
     */
    public function new(Request $request): Response
    {
        $step = new Steps();
        $form = $this->createForm(StepsType::class, $step);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($step);
            $entityManager->flush();

            return $this->redirectToRoute('steps_index');
        }

        return $this->render('steps/new.html.twig', [
            'step' => $step,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="steps_show", methods={"GET"})
     */
    public function show(Steps $step): Response
    {
        return $this->render('steps/show.html.twig', [
            'step' => $step,
        ]);
    }

    /**
     * @Route("/{id}/edit", name="steps_edit", methods={"GET","POST"})
     */
    public function edit(Request $request, Steps $step): Response
    {
        $form = $this->createForm(StepsType::class, $step);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('steps_index');
        }

        return $this->render('steps/edit.html.twig', [
            'step' => $step,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}", name="steps_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Steps $step): Response
    {
        if ($this->isCsrfTokenValid('delete'.$step->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($step);
            $entityManager->flush();
        }

        return $this->redirectToRoute('steps_index');
    }
}
