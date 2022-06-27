<?php
/**
 * User controller.
 */

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Service\UserService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

 /**
  * Class UserController.
  */
 #[Route('/user')]
class UserController extends AbstractController
{
    /**
     * User service.
     */
    private UserService $userService;

    /**
     * UserController constructor.
     *
     * @param UserService $userService User service
     */
    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Edit action.
     *
     * @param Request        $request        HTTP request
     * @param User           $user
     * @param UserRepository $userRepository
     *
     * @return Response HTTP response
     */
    #[Route('/{id}/edit', name: 'user_edit', requirements: ['id' => '[1-9]\d*'], methods: 'GET|PUT')]
     #[IsGranted('ROLE_ADMIN')]
    public function edit(Request $request, User $user, UserRepository $userRepository): Response
    {
        $this->getUser();
        $form = $this->createForm(UserType::class, $user, ['method' => 'PUT']);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $newPassword = $form->get('newPassword')->getData();
            $userRepository->save($user, $newPassword);
            $this->addFlash(
                'success',
                'message_updated_successfully'
            );

            return $this->redirectToRoute('recipe_index');
        }

        return $this->render('user/edit.html.twig', [
            'form' => $form->createView(),
            'user' => $user,
        ]);
    }
}
