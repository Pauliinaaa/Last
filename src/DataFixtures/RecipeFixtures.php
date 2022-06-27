<?php
/**
 * Recipe fixtures.
 */

namespace App\DataFixtures;

use App\Entity\Category;
use App\Entity\Recipe;
use Doctrine\Common\DataFixtures\DependentFixtureInterface;

/**
 * Class RecipeFixtures.
 */
class RecipeFixtures extends AbstractBaseFixtures implements DependentFixtureInterface
{
    /**
     * Load data.
     *
     * @psalm-suppress PossiblyNullPropertyFetch
     * @psalm-suppress PossiblyNullReference
     * @psalm-suppress UnusedClosureParam
     */
    public function loadData(): void
    {
        if (null === $this->manager || null === $this->faker) {
            return;
        }

        $this->createMany(100, 'recipes', function () {
            $recipe = new Recipe();
            $recipe->setName($this->faker->sentence);
            $recipe->setCreatedAt($this->faker->dateTimeBetween('-100 days', '-20 day'));
            $recipe->setUpdatedAt($this->faker->dateTimeBetween('-20 days', '-1 day'));
            $recipe->setContent($this->faker->text(1020));
            /** @var Category $category */
            $category = $this->getRandomReference('categories');
            $recipe->setCategory($category);

            return $recipe;
        });

        $this->manager->flush();
    }

    /**
     * This method must return an array of fixtures classes
     * on which the implementing class depends on.
     *
     * @return string[] of dependencies
     *
     * @psalm-return array{0: CategoryFixtures::class}
     */
    public function getDependencies(): array
    {
        return [CategoryFixtures::class];
    }
}
