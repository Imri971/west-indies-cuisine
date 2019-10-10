<?php

namespace App\Controller;

use App\Entity\Tag;
use App\Entity\Unit;
use App\Entity\Steps;
use App\Entity\Recipe;
use App\Entity\Reviews;
use App\Entity\Comments;
use App\Form\RecipeType;
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
use Doctrine\Common\Collections\ArrayCollection;
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
            4
        );
        return $this->render('recipe/index.html.twig', [
            'controller_name' => 'RecipeController',
            'recipes' => $recettes,
            'gallery' => $recipes,
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
     * @Route("/new", name="recipe_new", methods={"GET", "POST"})
     */
    public function new(Request $request): Response
    {
        $recipe = new Recipe();
        $user= $this->getUser();

        
            // $step = new Steps();
            // $step->setSpot(1)
            //      ->setDescription('Paris');

            // $recipe->addStep($step);   

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            if ($user) $recipe->setUser($user); 
            $recipe->setCreatedAt(new \DateTime());
            $steps= $recipe->getSteps();
            
            

            foreach ($steps as $step) {
                $step->setRecipe($recipe);
            }
            
            $entityManager->persist($recipe);
            $entityManager->flush();
            // $entityManager->persist($steps);
            // $entityManager->flush();
            // pour chaque step de r->steps:
            //     step->setR(r)
            // flush

            // foreach ($step as $key => $value) {
            //     # code...
            // }
            

            return $this->redirectToRoute('recipe_show', ['id' => $recipe->getId()]);
        }

        return $this->render('recipe/new.html.twig', [
            'recipes' => $recipe,
            'form' => $form->createView(),
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
     * Permet de liker ou unliker un article
     * @Route("/recipe/{id}/like", name="recipe_like", requirements={"id"="\d+"})
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
     * @Route("/recipe/{id}",  name="recipe_show")
     */
    public function show(Recipe $recipe, Comments $comments = null ,Request $request, ObjectManager $manager)
    {

        $comments = new Comments();
        
        $user= $this->getUser();
        
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
             
             $comments->setUser($user)
                      ->setAuthor($user->getUsername())
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
     * @Route("/recipe/{id}/edit", name="recipe_edit", methods={"GET","POST"})
     */
    public function editRecipe(Request $request, $id): Response
    {

        $entityManager= $this->getDoctrine()->getManager();
        $recipe = $this->getDoctrine()->getRepository(Recipe::class)->findOneBy(['id' => $id]);
        $user= $this->getUser();

        $originalStep = new ArrayCollection();

        foreach ($recipe->getSteps() as $step) {
            $originalStep->add($step);
            }

        $form = $this->createForm(RecipeType::class, $recipe);
        $form->handleRequest($request);

        
        if ($form->isSubmitted() && $form->isValid()) {
            

            if ($user) $recipe->setUser($user); 
            
             $steps= $recipe->getSteps();
            
            foreach ($steps as $step) {
                $step->setRecipe($recipe);
             }
           
             
            foreach ($originalStep as $step) {
                dump($recipe->getSteps()->contains($step));
               if ($recipe->getSteps()->contains($step) === false) {
                    
                    $entityManager->remove($step);
                } 
            }
            
            $entityManager->persist($recipe);
            $entityManager->flush();

        return $this->redirectToRoute('recipe_show',['id' => $recipe->getId()]);
        
        }

        return $this->render('recipe/edit.html.twig', [
            'recipe' => $recipe,
            'form' => $form->createView(),
        ]);
    }

   /**
     * @Route("/comments/{id}/edit", name="comments_edit", methods={"GET","POST"}, requirements={"id"="\d+"})
     * @Security("user.getUsername() == comment.getAuthor()")
     */
    public function editComment(Request $request, Comments $comment): Response
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

    /**
     * @Route("recipe/{id}", name="recipe_delete", methods={"DELETE"})
     */
    public function delete(Request $request, Recipe $recipe): Response
    {
        if ($this->isCsrfTokenValid('delete'.$recipe->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($recipe);
            $entityManager->flush();
        }

        return $this->redirectToRoute('recipe_show',['id' => $recipe->getId()]);
    }

    
}