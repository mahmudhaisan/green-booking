<?php
function custom_breadcrumbs() {
    // Home page URL
    $home_url = get_bloginfo('url');

    // Separator between breadcrumbs
    $separator = ' / ';

    // Start the breadcrumbs
    $breadcrumbs = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';

    // Home breadcrumb
    $breadcrumbs .= '<li class="breadcrumb-item"><a href="' . esc_url($home_url) . '">Home</a></li>';

    if (is_archive()) {
        $post_type = get_post_type();
        $post_type_obj = get_post_type_object($post_type);

        // Archive page breadcrumb
        $breadcrumbs .= '<li class="breadcrumb-item active" aria-current="page">' . $post_type_obj->label . '</li>';
    } elseif (is_single()) {
        // Single post breadcrumb
        $post = get_queried_object();

        $post_type = get_post_type($post);

        if ($post_type !== 'post') {
            $post_type_obj = get_post_type_object($post_type);
            $breadcrumbs .= '<li class="breadcrumb-item"><a href="' . get_post_type_archive_link($post_type) . '">' . $post_type_obj->label . '</a></li>';
        }

        $breadcrumbs .= '<li class="breadcrumb-item active" aria-current="page">' . get_the_title() . '</li>';
    }

    // Close the breadcrumbs
    $breadcrumbs .= '</ol></nav>';

    echo $breadcrumbs;
}