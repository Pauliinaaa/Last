<?php
/**
 * Recipe controller.
 */

namespace App\Controller;

use App\Entity\Recipe;
use App\Form\RecipeType;
use App\Service\RecipeService;
use Doctrine\ORM\ORMException;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class RecipeController.
 */
#[Route('/recipe')]
class RecipeController extends AbstractController
{
    /**
     * Recipe service.
     */
    private RecipeService $recipeService;

    /**
     * Constructor.
     *
     * @param RecipeService $recipeService Recipe service
     */
    public function __construct(RecipeService $recipeService)
    {
        $this->recipeService = $recipeService;
    }

    /**
     * Index action.
     *
     * @param Request $request HTTP Request
     *
     * @return Response HTTP response
     */
    #[Route(name: 'recipe_index', methods: 'GET')]
    public function index(Request $request): Response
    {
        $filters = [];
        $filters['category_id'] = $request->query->getInt('filters_category_id');

        $pagination = $this->recipeService->createPaginatedList(
            $request->query->getInt('page', 1),
            $filters
        );

        return $this->render('recipe/index.html.twig', ['pagination' => $pagination]);
    }

    /**
     * Show action.
     *
     * @param Recipe $recipe Recipe
     *
     * @return Response HTTP response
     */
    #[Route(
        '/{id}',
        name: 'recipe_show',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET'
    )]
    public function show(Recipe $recipe): Response
    {
        return $this->render(
            'recipe/show.html.twig',
            ['recipe' => $recipe]
        );
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     */
    #[Route('/create', name: 'recipe_create', methods: 'GET|POST')]
    #[IsGranted('ROLE_ADMIN')]
    public function create(Request $request): Response
    {
        $recipe = new Recipe();
        $form = $this->createForm(RecipeType::class, $recipe, ['action' => $this->generateUrl('recipe_create')]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recipeService->save($recipe);

            $this->addFlash(
                'success',
                'message.created_successfully'
            );

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render('recipe/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Edit action.
     *
     * @param Request $request HTTP request
     * @param Recipe  $recipe  Recipe entity
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     */
    #[Route('/{id}/edit', name: 'recipe_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
    #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, Recipe $recipe): Response
    {
        $form = $this->createForm(RecipeType::class, $recipe, [
            'method' => 'PUT',
            'action' => $this->generateUrl('recipe_edit', ['id' => $recipe->getId()]),
        ]);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recipeService->save($recipe);

            $this->addFlash(
                'success',
                'message.edited_successfully'
            );

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render('recipe/edit.html.twig', [
            'form' => $form->createView(),
            'recipe' => $recipe,
        ]);
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Recipe  $recipe  Recipe entity
     *
     * @return Response HTTP response
     *
     * @throws ORMException
     */
    #[Route('/{id}/delete', name: 'recipe_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Recipe $recipe): Response
    {
        if (!$this->recipeService->canBeDeleted($recipe)) {
            $this->addFlash(
                'warning',
                'message.cant_be_deleted'
            );

            return $this->redirectToRoute('recipe_index');
        }
        $form = $this->createForm(FormType::class, $recipe, [
            'method' => 'DELETE',
            'action' => $this->generateUrl('recipe_delete', ['id' => $recipe->getId()]),
        ]);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->recipeService->delete($recipe);

            $this->addFlash(
                'success',
                'message.deleted_successfully'
            );

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render('recipe/delete.html.twig', [
            'form' => $form->createView(),
            'recipe' => $recipe,
        ]);
    }
}
