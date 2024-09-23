<?php

namespace App\Controller;

use App\Service\CategoryService;

class CategoryController
{
    /** @var CategoryService Service for managing category-related operations */
    private CategoryService $categoryService;

    /**
     * CategoryController constructor.
     *
     * @param CategoryService $categoryService Service for managing category-related operations
     */
    public function __construct(CategoryService $categoryService)
    {
        $this->categoryService = $categoryService;
    }

    /**
     * Get the category tree with course counts and output as JSON.
     *
     * @return void
     */
    public function getCategoryTree(): void
    {
        echo json_encode($this->categoryService->getCategoryTree());
    }

    /**
     * Get all categories with course counts (without hierarchy) and output as JSON.
     *
     * @return void
     */
    public function getCategories(): void
    {
        echo json_encode($this->categoryService->getAllCategoriesWithCounts());
    }

    /**
     * Get a single category by ID with course count and output as JSON.
     *
     * @param string $category_id
     * @return void
     */
    public function getCategoryById(string $category_id): void
    {
        $category = $this->categoryService->getCategoryById($category_id);
        if ($category) {
            echo json_encode($category);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Category not found']);
        }
    }
}
