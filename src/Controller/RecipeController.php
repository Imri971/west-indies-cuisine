<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Unit;
use App\Entity\Steps;
use App\Entity\Recipe;
use App\Entity\Reviews;
use App\Entity\Comments;
use App\Entity\Recherche;
use App\Entity\Ingredient;
use App\Entity\RecipeLike;
use App\Form\CommentsType;
use App\Form\RechercheType;
use App\Entity\KitchenTools;
use App\Repository\TagRepository;
use App\Repository\UnitRepository;
use App\Repository\UserRepository;
use App\Repository\StepsRepository;
use App\Repository\RecipeRepository;

use App\Repository\ReviewsRepository;
use App\Repository\CommentsRepository;
use App\Repository\RechercheRepository;
use App\Repository\IngredientRepository;
use App\Repository\RecipeLikeRepository;
use App\Repository\KitchenToolsRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;

class RecipeController extends AbstractController
{
    /**
     * @Route("/", name="recipe")
     */
    public function index(RecipeRepository $recipeRepository,RechercheRepository $rechercheRepository, Request $request, UserRepository $userRepo, PaginatorInterface $paginator )
    {
        $recherche = new Recherche();
        $formSearch= $this->createform(RechercheType::class, $recherche);
        $formSearch->handleRequest($request);

        
        if ($formSearch->isSubmitted() && $formSearch->isValid()) {
           $datas = $formSearch->getData();
            $term = $recherche->getLibelle();
            $recipes = $recipeRepository->search($term);
        }
        else
        {
            $recipes = $recipeRepository->findAll();
        }


        $cat = $this->getDoctrine()->getRepository(Tag::class);
        $ust = $this->getDoctrine()->getRepository(KitchenTools::class);
        $ing= $this->getDoctrine()->getRepository(Ingredient::class);
        $rev = $this->getDoctrine()->getRepository(Reviews::class);
        $stp = $this->getDoctrine()->getRepository(Steps::class);
        $uni = $this->getDoctrine()->getRepository(Unit::class);
        
        $tags = $cat->findAll();
        $kitchenTools = $ust->findAll();
        $ingredients = $ing->findAll();
        $reviews = $rev->findAll();
        $steps = $stp->findAll();
        $units = $uni->findAll();
        $recherche= $rechercheRepository->findAll();

        // Paginate the results of the query
        $recettes = $paginator->paginate(
            // Doctrine Query, not results
            $recipes,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            2
        );
        return $this->render('recipe/index.html.twig', [
            'controller_name' => 'RecipeController',
            'recipes' => $recettes,
            'tags' => $tags,
            'kitchenTools' => $kitchenTools,
            'ingredient' => $ingredients,
            'reviews' => $reviews,
            'steps' => $steps,
            'unit' => $units,
            'recherche' => $recherche,
            'users' => $userRepo->findAll(),
            'formSearch' => $formSearch->createView()

        ]);
    }

    /**
     * Permet de liker ou unliker un article
     * @Route("/recipe/{id}/like", name="recipe_like")
     */
    public function like(Recipe $recipe, ObjectManager $manager, RecipeLikeRepository $likeRepo): Response
        {
            $user= $this->getUser();

            if (!$user) return $this->json([
                'code' => 403,
                'error' => "Unauthorized"
            ], 403);

            if ($recipe->isLikedByUser($user)){
                $like = $likeRepo->findOneBy([
                    'recipes' => $recipe,
                    'user' => $user
                ]);
                $manager->remove($like);
                $manager->flush();

                return $this->json([
                    'code' =>200,
                    'message' => 'Like bien supprimé',
                    'likes' => $likeRepo->count(['recipes' => $recipe]) //Recuperer tous les like appartenant à ce Recipe
                ],200);
            }

            $like = new RecipeLike();
            $like->setRecipes($recipe)
                 ->setUser($user);
            $manager->persist($like);
            $manager->flush();
            return $this->json([
                'code' => 200, 
                'message' => 'Like bien ajouté',
                'likes' => $likeRepo->count(['recipes' => $recipe])
            ], 200);
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
    public function show(Recipe $recipe, Comments $comments = null ,Request $request, ObjectManager $manager, UserInterface $user = null)
    {

        $comments = new Comments();
        
        
        $form= $this->createForm(CommentsType::class, $comments);    
        $form->handleRequest($request);

        $repository = $this->getDoctrine()->getManager()->getRepository(Recipe::class);
        $repositoryComment = $this->getDoctrine()->getManager()->getRepository(Comments::class);

        $idRecipe = $repository->findOneBy(array('id' => $recipe->getId()));
        
        $idComment = $repositoryComment->findOneBy(array('id' => $comments->getId()));
        if($form->isSubmitted() && $form->isValid()){
            // usually you'll want to make sure the user is authenticated first
            $this->denyAccessUnlessGranted('IS_AUTHENTICATED_FULLY');

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

   /**
     * @Route("/{id}/edit", name="comments_edit", methods={"GET","POST"})
     * @Security("user.getUsername() == comment.getAuthor()")
     */
    public function edit(Request $request, Comments $comment): Response
    {
        $idRecipe= $comment->getRecipe();
        $form = $this->createForm(CommentsType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            
            return $this->redirectToRoute('recipe_show',['id' => $idRecipe->getId()]);
        }

        return $this->render('comments/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView(),
        ]);
    }

}