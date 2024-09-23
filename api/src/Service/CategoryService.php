<?php

namespace App\Service;

use App\Repository\CategoryRepository;

class CategoryService
{
    /** @var CategoryRepository Repository for managing category data */
    private CategoryRepository $categoryRepository;

    /**
     * CategoryService constructor.
     *
     * @param CategoryRepository $categoryRepository Repository for managing category data
     */
    public function __construct(CategoryRepository $categoryRepository)
    {
        $this->categoryRepository = $categoryRepository;
    }

    /**
     * Get the category tree with course counts.
     *
     * @return array
     */
    public function getCategoryTree(): array
    {
        return $this->categoryRepository->getCategoryTreeWithCounts();
    }

    /**
     * Get all categories with course counts (without hierarchy).
     *
     * @return array
     */
    public function getAllCategoriesWithCounts(): array
    {
        return $this->categoryRepository->getAllCategoriesWithCounts();
    }

    /**
     * Get a single category by ID with course count (without children or parent).
     *
     * @param string $categoryId
     * @return array|null
     */
    public function getCategoryById(string $categoryId): ?array
    {
        return $this->categoryRepository->getCategoryByIdWithCount($categoryId);
    }
}
