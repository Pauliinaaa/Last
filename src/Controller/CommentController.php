<?php
/**
 * Comment controller.
 */

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Recipe;
use App\Form\CommentType;
use App\Service\CommentService;
use App\Service\RecipeService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\FormType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class CommentController.
 *
 * @Route("/comment")
 */
class CommentController extends AbstractController
{
    /**
     * Comment service.
     */
    private CommentService $commentService;

    /**
     * Recipe service.
     */
    private RecipeService $recipeService;

    /**
     * CommentController constructor.
     *
     * @param CommentService $commentService Comment service
     * @param RecipeService  $recipeService  Recipe service
     */
    public function __construct(CommentService $commentService, RecipeService $recipeService)
    {
        $this->commentService = $commentService;
        $this->recipeService = $recipeService;
    }

    /**
     * Create action.
     *
     * @param Request $request HTTP request
     * @param Recipe  $recipe
     *
     * @return Response HTTP response
     */
    #[Route(
        '/recipe/{id}',
        name: 'comment_create',
        requirements: ['id' => '[1-9]\d*'],
        methods: 'GET|POST'
    )]
    public function create(Request $request, Recipe $recipe): Response
    {
        $recipeId = $recipe->getId();

        $comment = new Comment();
        $comment->setRecipe($recipe);

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->save($comment);
            $this->addFlash('success', 'message_created_successfully');

            return $this->redirectToRoute('recipe_show', ['id' => $recipeId]);
        }

        return $this->render(
            'comment/create.html.twig',
            ['form' => $form->createView(), 'id' => $recipeId]
        );
    }

    /**
     * Delete action.
     *
     * @param Request $request HTTP request
     * @param Comment $comment
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/delete', name: 'comment_delete', requirements: ['id' => '[1-9]\d*'], methods: 'GET|DELETE')]
    #[IsGranted('ROLE_ADMIN')]
    public function delete(Request $request, Comment $comment): Response
    {
        $form = $this->createForm(FormType::class, $comment, ['method' => 'DELETE']);
        $form->handleRequest($request);

        if ($request->isMethod('DELETE') && !$form->isSubmitted()) {
            $form->submit($request->request->get($form->getName()));
        }

        if ($form->isSubmitted() && $form->isValid()) {
            $this->commentService->delete($comment);
            $this->addFlash('success', 'message.deleted_successfully');

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render(
            'comment/delete.html.twig',
            [
                'form' => $form->createView(),
                'comment' => $comment,
            ]
        );
    }
}
