<?php

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\SearchType;
use App\Repository\RecipeRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class SearchController extends AbstractController
{
    /**
     * @Route("/search", name="search_recipe", methods= {"GET","POST"})
     */
    public function index(Request $request, RecipeRepository $recipeRepository, ObjectManager $manager )
    {
       $em = $this->getDoctrine()->getManager();
       $nom= $request->query->get('title');
       $repo = $em->getRepository(Recipe::class)->findBy(array('title'=>$nom));
       
       $query = $repo->CreateQueryBuilder('a')
                     ->andWhere ('a.title like :nom')
                     ->setParameter('nom','%'.$nom.'%')
                     ->orderby ('a.id', 'ASC')
                     ->getQuery();
        
        $recettes = $query->getResult();


       $recipes = $paginator->paginate(
        // Doctrine Query, not results
        $recettes,
        // Define the page parameter
        $request->query->getInt('page', 1),
        // Items per page
        2
    );
        return $this->render('search/index.html.twig', [
            'recettes' => $recipes

        ]);
    }
}
