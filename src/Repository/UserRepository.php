<?php
/**
 * User repository.
 */

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\DBAL\Query\QueryBuilder;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * @method User|null find($id, $lockMode = null, $lockVersion = null)
 * @method User|null findOneBy(array $criteria, array $orderBy = null)
 * @method User[]    findAll()
 * @method User[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    /**
     * Password hasher.
     */
    private UserPasswordHasherInterface $passwordHasher;

    /**
     * UserRepository constructor.
     * @param ManagerRegistry             $registry       Manager registry
     *
     * @param UserPasswordHasherInterface $passwordHasher
     */
    public function __construct(ManagerRegistry $registry, UserPasswordHasherInterface $passwordHasher)
    {
        $this->passwordHasher = $passwordHasher;
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     *
     * @param PasswordAuthenticatedUserInterface $user
     *
     * @param string                             $newHashedPassword
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', \get_class($user)));
        }

        $user->setPassword($newHashedPassword);
        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Save user.
     *@param User        $user        User entity
     *
     * @param string|null $newPassword
     */
    public function save(User $user, string $newPassword = null)
    {
        if ($newPassword) {
            $user->setPassword(
                $this->passwordHasher->hashPassword(
                    $user,
                    $newPassword
                )
            );
        }

        $this->_em->persist($user);
        $this->_em->flush();
    }

    /**
     * Query all records.
     *
     * @return QueryBuilder Query builder
     *
     * @noinspection PhpUnused
     */
    public function queryAll(): QueryBuilder
    {
        return $this->getOrCreateQueryBuilder()
            ->orderBy('user.email', 'ASC');
    }

    /**
     * Get or create new query builder.
     *
     * @param QueryBuilder|null $queryBuilder Query builder
     *
     * @return QueryBuilder|\Doctrine\ORM\QueryBuilder
     */
    private function getOrCreateQueryBuilder(QueryBuilder $queryBuilder = null): QueryBuilder|\Doctrine\ORM\QueryBuilder
    {
        return $queryBuilder ?? $this->createQueryBuilder('user');
    }
}
