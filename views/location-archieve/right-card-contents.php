<!-- Right Column: Post List -->
<div class="col-md-8 archieve-right-items">
    <div class="spinner-container" id="loading-spinner" style="display: none;">
        <div class="spinner"></div>
        bezig met laden
    </div>

    <div class="row">
        <?php
        if (isset($_POST['filter-button'])) {
            // Sanitize and retrieve user inputs
            $selectedLocation = sanitize_text_field($_POST['filter1']);
            $selectedLat = sanitize_text_field($_POST['selected-lat']);
            $selectedLng = sanitize_text_field($_POST['selected-lng']);
            $selectedRadius = intval($_POST['radius-select']); // Get the selected radius

            // Custom query to filter by location
            $args = array(
                'post_type' => 'locations', // Replace with your CPT slug
                'posts_per_page' => -1, // Get all posts
            );

            $query = new WP_Query($args);
            $filteredPosts = array();

            if ($query->have_posts()) {

                while ($query->have_posts()) {
                    $query->the_post();

                    // Get the latitude and longitude of the current post
                    $postLat = get_field('location_lat');
                    $postLng = get_field('location_lng');

                    // Calculate the distance between the selected location and the post location
                    $distance = calculateDistance($selectedLat, $selectedLng, $postLat, $postLng);

                    // Check if the post is within the selected radius
                    if ($distance <= $selectedRadius) {
                        $filteredPosts[] = get_the_ID();
                    }
                }


                if (empty($filteredPosts)) {
                    return;
                }

                $filtered_args = array(
                    'post_type' => 'locations', // Replace with your custom post type if needed
                    'post__in' => $filteredPosts, // Include only posts with IDs in the $filteredPosts array
                    'posts_per_page' => -1, // Retrieve all matching posts
                );

                $filtered_query = new WP_Query($filtered_args);


                if ($filtered_query->have_posts()) {
                    while ($filtered_query->have_posts()) {
                        $filtered_query->the_post();

                        include GRBP_PLUGINS_PATH . '/views/location-archieve/filtered-location-info-card.php';
                    }
                    wp_reset_postdata(); // Restore the global post data
                } else {
                    // No posts found
                }
            } else {
                echo 'No matching posts found.';
            }
        } else {
            // Display all posts by default
            if (have_posts()) {
                while (have_posts()) {
                    the_post();
                    include GRBP_PLUGINS_PATH . '/views/location-archieve/filtered-location-info-card.php';
                }
            } else {
                echo 'No posts found.';
            }
        }
        ?>
    </div>
</div>