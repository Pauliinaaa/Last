<?php
/**
 * Recipe service.
 */

namespace App\Service;

use App\Entity\Recipe;
use App\Repository\CommentRepository;
use App\Repository\RecipeRepository;
use Doctrine\ORM\NonUniqueResultException;
use Doctrine\ORM\NoResultException;
use Knp\Component\Pager\Pagination\PaginationInterface;
use Knp\Component\Pager\PaginatorInterface;

/**
 * Class RecipeService.
 */
class RecipeService
{
    /**
     * Recipe repository.
     */
    private RecipeRepository $recipeRepository;

    /**
     * Comment repository.
     */
    private CommentRepository $commentRepository;

    /**
     * Paginator.
     */
    private PaginatorInterface $paginator;

    /**
     * Category service.
     */
    private CategoryService $categoryService;

    /**
     * RecipeService constructor.
     *
     * @param RecipeRepository   $recipeRepository  Recipe repository
     * @param PaginatorInterface $paginator         Paginator
     * @param CategoryService    $categoryService   Category service
     * @param CommentRepository  $commentRepository Comment repository
     */
    public function __construct(RecipeRepository $recipeRepository, PaginatorInterface $paginator, CategoryService $categoryService, CommentRepository $commentRepository)
    {
        $this->recipeRepository = $recipeRepository;
        $this->paginator = $paginator;
        $this->categoryService = $categoryService;
        $this->commentRepository = $commentRepository;
    }

    /**
     * Create paginated list.
     *
     * @param int   $page    Page number
     * @param array $filters Filters array
     *
     * @return PaginationInterface<string, mixed> Paginated list
     */
    public function createPaginatedList(int $page, array $filters = []): PaginationInterface
    {
        $filters = $this->prepareFilters($filters);

        return $this->paginator->paginate(
            $this->recipeRepository->queryAll($filters),
            $page,
            RecipeRepository::PAGINATOR_ITEMS_PER_PAGE
        );
    }

    /**
     * Save recipe.
     *
     * @param Recipe $recipe Recipe entity
     */
    public function save(Recipe $recipe): void
    {
        $this->recipeRepository->save($recipe);
    }

    /**
     * Delete recipe.
     *
     * @param Recipe $recipe Recipe entity
     */
    public function delete(Recipe $recipe): void
    {
        $this->recipeRepository->delete($recipe);
    }

    /**
     * @param Recipe $recipe
     *
     * @return bool
     *
     * @throws NoResultException
     * @throws NonUniqueResultException
     */
    public function canBeDeleted(Recipe $recipe): bool
    {
        if ($this->commentRepository->countComments($recipe)) {
            return false;
        }

        return true;
    }

    /**
     * @param int $id
     *
     * @return Recipe|null
     */
    public function findOneById(int $id): ?Recipe
    {
        return $this->recipeRepository->findOneById($id);
    }

    /**
     * Prepare filters for the recipes list.
     *
     * @param array $filters Raw filters from request
     *
     * @return array Result array of filters
     */
    private function prepareFilters(array $filters): array
    {
        $resultFilters = [];
        if (isset($filters['category_id']) && is_numeric($filters['category_id'])) {
            $category = $this->categoryService->findOneById(
                $filters['category_id']
            );
            if (null !== $category) {
                $resultFilters['category'] = $category;
            }
        }

        return $resultFilters;
    }
}
