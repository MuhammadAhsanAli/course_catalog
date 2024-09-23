<?php

namespace App;

class ErrorHandler
{
    /**
     * Handle 404 errors when a route is not found.
     */
    public static function handle404(): void
    {
        header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
        echo "No route found.";
    }

    /**
     * Handle cases where a controller is not found.
     */
    public static function handleControllerNotFound(): void
    {
        header($_SERVER["SERVER_PROTOCOL"] . ' 404 Not Found');
        echo "Controller not found.";
    }

    /**
     * Handle invalid action errors.
     */
    public static function handleInvalidAction(): void
    {
        header($_SERVER["SERVER_PROTOCOL"] . ' 500 Internal Server Error');
        echo "Invalid action.";
    }
}
