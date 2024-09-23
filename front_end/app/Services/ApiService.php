<?php

namespace App\Services;

use Exception;

class ApiService
{
    /**
     * @var string Base URL for the API.
     */
    private string $apiUrl;

    public function __construct()
    {
        $this->apiUrl = $_ENV['API_URL'];
    }

    /**
     * Fetch categories from the API.
     *
     * @return array The list of categories.
     * @throws Exception
     */
    public function fetchCategories(): array
    {
        return $this->fetchFromApi('categories/tree');
    }

    /**
     * Fetch courses from the API, optionally filtered by category.
     *
     * @param string|null $categoryId The category ID to filter courses.
     * @return array The list of courses.
     * @throws Exception
     */
    public function fetchCourses(?string $categoryId = null): array
    {
        $endpoint = 'courses' . ($categoryId ? '?category_id=' . urlencode($categoryId) : '');
        return $this->fetchFromApi($endpoint);
    }

    /**
     * Fetch data from the API.
     *
     * @param string $endpoint The API endpoint to fetch data from.
     * @return array The decoded JSON response.
     * @throws Exception If the API request fails.
     */
    private function fetchFromApi(string $endpoint): array
    {
        $response = file_get_contents($this->apiUrl . $endpoint);
        if ($response === false) {
            throw new Exception('Failed to fetch data from API: ' . $this->apiUrl . $endpoint);
        }
        return json_decode($response, true);
    }
}
