<?php

namespace App\Repository;

use PDO;

class CategoryRepository
{
    /** @var PDO Database connection instance */
    private PDO $pdo;

    /**
     * CategoryRepository constructor.
     *
     * @param PDO $pdo Database connection instance
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute( PDO::ATTR_EMULATE_PREPARES, true );
    }

    /**
     * Get the category tree with course counts.
     *
     * @return array
     */
    public function getCategoryTreeWithCounts(): array
    {
        return $this->buildCategoryTree($this->getAllCategoriesWithCounts());
    }

    /**
     * Get all categories with course counts.
     *
     * @return array
     */
    public function getAllCategoriesWithCounts(): array
    {
        return $this->fetchCategoriesWithCounts();
    }

    /**
     * Get a single category by ID with course count.
     *
     * @param string $categoryId
     * @return array|null
     */
    public function getCategoryByIdWithCount(string $categoryId): ?array
    {
        return $this->fetchCategoryWithCount($categoryId);
    }

    /**
     * Fetch all categories with course counts.
     *
     * @return array
     */
    private function fetchCategoriesWithCounts(): array
    {
        $stmt = $this->pdo->query("SELECT id FROM categories");
        $categories = [];

        foreach ($stmt->fetchAll(PDO::FETCH_ASSOC) as $row) {
            $categories[] = $this->fetchCategoryWithCount($row['id']);
        }

        return $categories;
    }

    /**
     * Fetch a category by its ID along with the count of associated courses.
     *
     * @param string $categoryId The ID of the category to fetch.
     * @return array|null The category data or null if not found.
     */
    public function fetchCategoryWithCount(string $categoryId): ?array
    {
        $query = $this->buildCategoryQuery();

        // Prepare and execute the query
        return $this->executeCategoryQuery($query, $categoryId);
    }

    /**
     * Build the SQL query to retrieve category and course count.
     *
     * @return string The SQL query string.
     */
    private function buildCategoryQuery(): string
    {
        return '
            WITH RECURSIVE category_tree AS (
                SELECT
                    id,
                    name,
                    parent,
                    created_at,
                    updated_at
                FROM categories
                WHERE id = :categoryId 

                UNION ALL
                
                SELECT
                    c.id,
                    c.name,
                    c.parent,
                    c.created_at,
                    c.updated_at
                FROM categories c
                INNER JOIN category_tree ct ON c.parent = ct.id
            )
            SELECT
                ct.id,
                ct.name,
                ct.parent AS parent_id,
                COUNT(DISTINCT c.course_id) AS count_of_courses,
                MAX(ct.created_at) AS created_at,
                MAX(ct.updated_at) AS updated_at
            FROM category_tree ct
            LEFT JOIN courses c ON c.category_id IN (SELECT id FROM category_tree)
            GROUP BY ct.id, ct.name, ct.parent
            HAVING ct.id = :categoryId
        ';
    }

    /**
     * Execute the prepared SQL query for category retrieval.
     *
     * @param string $query The SQL query to execute.
     * @param string $categoryId The category ID to bind.
     * @return array|null The category data or null if not found.
     */
    private function executeCategoryQuery(string $query, string $categoryId): ?array
    {
        $stmt = $this->pdo->prepare($query);
        $stmt->bindParam(':categoryId', $categoryId);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
    }

    /**
     * Build a hierarchical category tree.
     *
     * @param array $categories
     * @param string|null $parentId
     * @return array
     */
    protected function buildCategoryTree(array $categories, ?string $parentId = null): array
    {
        $branch = [];

        foreach ($categories as $category) {
            if ($category['parent_id'] === $parentId) {
                $children = $this->buildCategoryTree($categories, $category['id']);
                if ($children) {
                    $category['children'] = $children;
                }
                $branch[] = $category;
            }
        }

        return $branch;
    }
}
