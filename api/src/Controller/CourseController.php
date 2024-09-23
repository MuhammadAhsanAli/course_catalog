<?php

namespace App\Controller;

use App\Service\CourseService;

class CourseController
{
    /** @var CourseService Service for managing course-related operations */
    private CourseService $courseService;

    /**
     * CourseController constructor.
     *
     * @param CourseService $courseService Service for managing course-related operations
     */
    public function __construct(CourseService $courseService)
    {
        $this->courseService = $courseService;
    }

    /**
     * Get all courses or filter by category.
     *
     * @param string|null $course_id Optional course ID to filter results
     * @return void
     */
    public function getCourses(?string $course_id = null): void
    {
        $courses = $this->courseService->getCourses($course_id);
        if ($courses) {
            echo json_encode($courses);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Course not found']);
        }
    }
}
