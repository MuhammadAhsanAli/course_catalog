<?php
declare(strict_types=1);

// Autoload dependencies using Composer
require_once __DIR__ . '/vendor/autoload.php';

use App\Services\ApiService;

// Load environment variables using Dotenv
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Instantiate the API service to fetch data
$apiService = new ApiService();

// Route request to the course catalog view
require_once __DIR__ . '/app/views/course_catalog.php';
