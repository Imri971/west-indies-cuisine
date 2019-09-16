<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Unit;
use App\Entity\Steps;
use App\Entity\Recipe;
use App\Entity\Reviews;
use App\Entity\Comments;
use App\Form\CommentType;
use App\Entity\Ingredient;
use App\Entity\KitchenTools;
use App\Repository\TagRepository;
use App\Repository\UnitRepository;
use App\Repository\StepsRepository;
use App\Repository\RecipeRepository;
use App\Repository\ReviewsRepository;
use App\Repository\CommentsRepository;
use App\Repository\IngredientRepository;
use App\Repository\KitchenToolsRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RecipeController extends AbstractController
{
    /**
     * @Route("/", name="recipe")
     */
    public function index()
    {
        $rec = $this->getDoctrine()->getRepository(Recipe::class);
        $cat = $this->getDoctrine()->getRepository(Tag::class);
        $ust = $this->getDoctrine()->getRepository(KitchenTools::class);
        $ing= $this->getDoctrine()->getRepository(Ingredient::class);
        $rev = $this->getDoctrine()->getRepository(Reviews::class);
        $stp = $this->getDoctrine()->getRepository(Steps::class);
        $uni = $this->getDoctrine()->getRepository(Unit::class);
        
        $recipes = $rec->findAll();
        $tags = $cat->findAll();
        $kitchenTools = $ust->findAll();
        $ingredients = $ing->findAll();
        $reviews = $rev->findAll();
        $steps = $stp->findAll();
        $units = $uni->findAll();

        return $this->render('recipe/index.html.twig', [
            'controller_name' => 'RecipeController',
            'recipes' => $recipes,
            'tags' => $tags,
            'kitchenTools' => $kitchenTools,
            'ingredient' => $ingredients,
            'reviews' => $reviews,
            'steps' => $steps,
            'unit' => $units

        ]);
    }

    /**
     * @Route("/bdd", name="bdd")
     */
    public function bdd()
    {
        $rec = $this->getDoctrine()->getRepository(Recipe::class);
        $cat = $this->getDoctrine()->getRepository(Tag::class);
        $ust = $this->getDoctrine()->getRepository(KitchenTools::class);
        $ing= $this->getDoctrine()->getRepository(Ingredient::class);
        $rev = $this->getDoctrine()->getRepository(Reviews::class);
        $stp = $this->getDoctrine()->getRepository(Steps::class);
        $uni = $this->getDoctrine()->getRepository(Unit::class);
        
        $recipes = $rec->findAll();
        $tags = $cat->findAll();
        $kitchenTools = $ust->findAll();
        $ingredients = $ing->findAll();
        $reviews = $rev->findAll();
        $steps = $stp->findAll();
        $units = $uni->findAll();
        
        return $this->render('recipe/bdd.html.twig', [
            'controller_name' => 'RecipeController',
            'recipes' => $recipes,
            'tags' => $tags,
            'kitchenTools' => $kitchenTools,
            'ingredient' => $ingredients,
            'reviews' => $reviews,
            'steps' => $steps,
            'unit' => $units
        ]);
    }

    /**
     * @Route("/recipe/{id}",  name="recipe_show")
     */
    public function show(Recipe $recipe, Comments $comments = null , Request $request, ObjectManager $manager, UserInterface $user = null){

        $comments = new Comments();
        $form= $this->createForm(CommentType::class, $comments);    
        $form->handleRequest($request);
        $repository = $this->getDoctrine()
                   ->getManager()
                   ->getRepository(Recipe::class);
        $idRecipe = $repository->findOneBy(array('id' => $recipe->getId()));
        if($form->isSubmitted() && $form->isValid()){
              $comments->setCreatedAt(new \DateTime());
              $comments->setRecipe($idRecipe);
              //$comments->setPicture('<img src="http://lorempixel.com/400/200" alt="">');
             
             $comments->setAuthor($user->getUsername())
                      ->setEmail($user->getEmail())
                      ->setPicture($user->getPicture());

                 
              $manager->persist($comments);
              $manager->flush();

              return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId()]);
        }
        return $this->render('recipe/page.html.twig', [
            'controller_name' => 'RecipeController',
            'recipes' => $recipe,
            'formRecipe' => $form->createView(),
            'comments' => $comments
            
        ]);
       
    }

   
}