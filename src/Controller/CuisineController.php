<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class CuisineController extends AbstractController
{
    /**
     * @Route("/cuisine", name="cuisine")
     */
    public function index()
    {
        return $this->render('cuisine/index.html.twig', [
            'controller_name' => 'CuisineController',
        ]);
    }
}
