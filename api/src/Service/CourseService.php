<?php

namespace App\Service;

use App\Repository\CourseRepository;

class CourseService
{
    /** @var CourseRepository Repository for managing course data */
    private CourseRepository $courseRepository;

    /**
     * CourseService constructor.
     *
     * @param CourseRepository $courseRepository Repository for managing course data
     */
    public function __construct(CourseRepository $courseRepository)
    {
        $this->courseRepository = $courseRepository;
    }

    /**
     * Get courses by category or course ID.
     *
     * @param string|null $courseId Optional course ID to fetch a specific course
     * @return array|null
     */
    public function getCourses(?string $courseId = null): ?array
    {
        if ($courseId !== null) {
            return $this->courseRepository->findCourseById($courseId);
        } elseif (isset($_GET['category_id'])) {
            return $this->courseRepository->findByCategory($_GET['category_id']);
        } else {
            return $this->courseRepository->findAll();
        }
    }
}
