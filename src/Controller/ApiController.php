<?php

namespace App\Controller;

use App\Repository\RecipeRepository;
use JMS\Serializer\SerializerInterface;
use JMS\Serializer\SerializationContext;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

/**
 * @Route("/api")
 */
class ApiController extends AbstractController
{
    /**
     * @Route("/recipes", name="apirecipe")
     */
    public function index(RecipeRepository $recipeRepository, SerializerInterface $serializer)
    {
        $response = new JsonResponse();

        return $response->setContent(
            $serializer->serialize(
                $recipeRepository->findAll(),
                'json',
                SerializationContext::create()->setGroups(['list'])
            )
        );
    }

    /**
     * @Route("/recipes/{id}", name="recipeShow")
     */
    public function show(RecipeRepository $recipeRepository, SerializerInterface $serializer, $id)
    {
        $response = new JsonResponse();

        return $response->setContent(
            $serializer->serialize(
                $recipeRepository->find($id),
                'json',
                SerializationContext::create()->setGroups(['show'])
            )
        );
    }
}
