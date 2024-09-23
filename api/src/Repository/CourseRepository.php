<?php

namespace App\Repository;

use PDO;

class CourseRepository
{
    /** @var PDO Database connection instance */
    private PDO $pdo;

    /**
     * CourseRepository constructor.
     *
     * @param PDO $pdo Database connection instance
     */
    public function __construct(PDO $pdo)
    {
        $this->pdo = $pdo;
        $this->pdo->setAttribute(PDO::ATTR_EMULATE_PREPARES, true);
    }

    /**
     * Get all courses.
     *
     * @return array
     */
    public function findAll(): array
    {
        return $this->getCourses();
    }

    /**
     * Get courses by category.
     *
     * @param string $categoryId Category ID to filter courses
     * @return array
     */
    public function findByCategory(string $categoryId): array
    {
        return $this->getCourses($categoryId);
    }

    /**
     * Get a single course by its ID.
     *
     * @param string $courseId Course ID to fetch
     * @return array|null
     */
    public function findCourseById(string $courseId): ?array
    {
        $courses = $this->getCourses(null, $courseId);
        return $courses[0] ?? null; // Return the first result or null
    }

    /**
     * Retrieve courses based on optional category and course IDs.
     *
     * @param string|null $categoryId The ID of the category to filter courses.
     * @param string|null $courseId The ID of the specific course to retrieve.
     * @return array An array of course data.
     */
    public function getCourses(?string $categoryId = null, ?string $courseId = null): array
    {
        // Build the base query
        $query = $this->buildBaseQuery();

        // Add recursion for category filtering if categoryId is provided
        if ($categoryId !== null) {
            $query .= $this->addCategoryRecursion();
        }

        // Add the main query for courses
        $query .= $this->buildCourseQuery();

        // Add conditions based on filters
        $query .= $this->addConditions($categoryId, $courseId);

        // Prepare and execute the query
        return $this->executeQuery($query, $categoryId, $courseId);
    }

    /**
     * Build the base query for category hierarchy.
     *
     * @return string The base SQL query.
     */
    private function buildBaseQuery(): string
    {
        return '
            WITH RECURSIVE category_hierarchy AS (
                SELECT id, name, parent, name AS top_category_name
                FROM categories
                WHERE parent IS NULL

                UNION ALL

                SELECT c.id, c.name, c.parent, ch.top_category_name
                FROM categories c
                JOIN category_hierarchy ch ON c.parent = ch.id
            )';
    }

    /**
     * Add recursive category selection for a given category ID.
     *
     * @return string The SQL fragment for category recursion.
     */
    private function addCategoryRecursion(): string
    {
        return ',
            all_categories AS (
                SELECT id
                FROM category_hierarchy
                WHERE id = :categoryId

                UNION ALL

                SELECT c.id
                FROM categories c
                JOIN all_categories ac ON c.parent = ac.id
            )';
    }

    /**
     * Build the main query for retrieving course details.
     *
     * @return string The SQL query for courses.
     */
    private function buildCourseQuery(): string
    {
        return '
            SELECT 
                courses.course_id,
                courses.title AS name,
                courses.description,
                courses.image_preview AS preview,
                ch.top_category_name AS main_category_name,
                courses.created_at,
                courses.updated_at 
            FROM 
                courses
            LEFT JOIN 
                category_hierarchy ch ON courses.category_id = ch.id';
    }

    /**
     * Add conditions to the query based on optional parameters.
     *
     * @param string|null $categoryId The category ID for filtering.
     * @param string|null $courseId The course ID for filtering.
     * @return string The SQL fragment with conditions.
     */
    private function addConditions(?string $categoryId, ?string $courseId): string
    {
        $conditions = [];

        if ($categoryId !== null) {
            $conditions[] = 'EXISTS (SELECT 1 FROM all_categories WHERE id = courses.category_id)';
        }
        if ($courseId !== null) {
            $conditions[] = 'courses.course_id = :courseId';
        }

        return !empty($conditions) ? ' WHERE ' . implode(' AND ', $conditions) . ' ORDER BY courses.course_id;' : ' ORDER BY courses.course_id;';
    }

    /**
     * Execute the prepared SQL query with bound parameters.
     *
     * @param string $query The SQL query to execute.
     * @param string|null $categoryId The category ID to bind.
     * @param string|null $courseId The course ID to bind.
     * @return array An array of course data.
     */
    private function executeQuery(string $query, ?string $categoryId, ?string $courseId): array
    {
        $stmt = $this->pdo->prepare($query);

        // Bind parameters if provided
        if ($categoryId !== null) {
            $stmt->bindParam(':categoryId', $categoryId);
        }
        if ($courseId !== null) {
            $stmt->bindParam(':courseId', $courseId);
        }

        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
