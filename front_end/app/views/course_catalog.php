<?php
// Fetch the category ID from the query string, defaulting to null if not present.
$category_id = $_GET['category_id'] ?? null;

// Fetch categories and courses using the API service
$categories = $apiService->fetchCategories();
$courses = $apiService->fetchCourses($category_id);

// Include the category rendering logic
require_once __DIR__.'/partials/category_renderer.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Course Catalog</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <link href="app/views/assets/css/style.css" rel="stylesheet">
</head>
<body class="bg-light p-4">
<div class="container">
    <div class="text-center mb-5">
        <h1 class="display-6">Course Catalog</h1>
    </div>
    <div class="row">
        <!-- Category Sidebar -->
        <div class="col-md-3 mb-4 sidebar">
            <?php renderCategories($categories); // Render the categories sidebar ?>
        </div>

        <!-- Courses Section -->
        <div class="col-md-9">
            <div class="row">
                <?php foreach ($courses as $course): ?>
                    <div class="col-lg-4 mb-4">
                        <div class="card">
                            <img class="img-fluid card-img-top" src="<?= htmlspecialchars($course['preview']) ?>" alt="<?= htmlspecialchars($course['name']) ?>">
                            <span class="badge badge-secondary badge-top-right"><?= htmlspecialchars($course['main_category_name'] ?? '') ?></span>
                            <div class="card-body">
                                <h5 class="card-title font-medium title-truncate"><?= htmlspecialchars($course['name']) ?></h5>
                                <p class="card-text font-small text-justify description-truncate"><?= htmlspecialchars($course['description']) ?></p>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>

<!-- Scripts -->
<script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.1/dist/umd/popper.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
</body>
</html>
