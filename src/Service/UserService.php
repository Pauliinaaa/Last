<?php
/**
 * User service.
 */

namespace App\Service;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Class UserService.
 */
class UserService
{
    /**
     * User repository.
     */
    private UserRepository $userRepository;

    /**
     * UserPasswordHasherInterference.
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * UserService constructor.
     *
     * @param UserRepository              $userRepository
     * @param UserPasswordHasherInterface $passwordHasher

     */
    public function __construct(UserRepository $userRepository, UserPasswordHasherInterface $passwordHasher)
    {
        $this->userRepository = $userRepository;
        $this->passwordHasher = $passwordHasher;
    }

    /**
     * Save user.
     *
     * @param User $user User entity
     */
    public function save(User $user): void
    {
        $this->userRepository->save($user);
    }
}
