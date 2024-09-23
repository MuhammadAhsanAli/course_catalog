<?php

/**
 * Render categories recursively.
 *
 * @param array $categories The categories to render.
 * @return void
 */
function renderCategories(array $categories): void
{
    if (empty($categories)) {
        return;
    }

    echo '<ul class="list-unstyled">';

    foreach ($categories as $category) {
        echo '<li>';
        echo '<a href="?category_id=' . htmlspecialchars($category['id']) . '">' . htmlspecialchars($category['name']);
        echo $category['count_of_courses'] > 0 ? " ({$category['count_of_courses']})" : '';
        echo '</a>';

        // Render child categories recursively if they exist
        if (!empty($category['children'])) {
            renderCategories($category['children']);
        }

        echo '</li>';
    }

    echo '</ul>';
}
