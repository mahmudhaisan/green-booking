<?php
get_header();
?>

<div class="container mt-4 locations-archive-body">
    <div class="filter-heading">
        <h3><?php custom_breadcrumbs(); ?></h3>
        <h3>Aangesloten locaties</h3>
    </div>
    <div class="row mb-5">
        <!-- Left Column: Search Filters -->
        <div class="col-md-4">
            <div class="card border border-1">
                <div class="card-body location-filter-top">
                    <div class="radius-based-filter">
                        <h5 class="card-title">Zoek locaties</h5>
                        <!-- Add your search filter inputs and submit button here -->
                        <form method="POST" action="">
                            <div class="form-group">
                                <input type="text" class="form-control" id="location-input" name="filter1">
                                <input type="hidden" id="selected-lat" name="selected-lat">
                                <input type="hidden" id="selected-lng" name="selected-lng">
                            </div>

                            <div class="form-group">
                                <select class="form-control mt-3" id="radius-select" name="radius-select">
                                    <option value="10">+10km</option>
                                    <option value="15">+15km</option>
                                    <option value="20">+20km</option>
                                </select>
                            </div>

                            <div class="form-group">
                                <button type="submit" class="btn btn-success text-white mt-3" id="filter-button" name="filter-button">Submit</button>
                            </div>
                        </form>

                        <div id="map"></div>

                        <div id="results-list"></div>

                        <!-- JavaScript for Google Maps Autocomplete and Map Display -->
                        <script>
                            function initMap() {
                                var map = new google.maps.Map(document.getElementById('map'), {
                                    center: {
                                        lat: 23.8041,
                                        lng: 90.4152
                                    },
                                    zoom: 15
                                });

                                var autocomplete = new google.maps.places.Autocomplete(document.getElementById('location-input'), {
                                    types: ['(regions)'], // Restrict to regions (countries)
                                    componentRestrictions: {
                                        'country': 'NL'
                                    } // Restrict to Netherlands (NL)
                                });

                                autocomplete.addListener('place_changed', function() {
                                    var place = autocomplete.getPlace();
                                    if (place.geometry) {
                                        var lat = place.geometry.location.lat(); // Get the full latitude
                                        var lng = place.geometry.location.lng(); // Get the full longitude
                                        document.getElementById('selected-lat').value = lat;
                                        document.getElementById('selected-lng').value = lng;
                                    }
                                });
                            }
                        </script>
                    </div>
                </div>

                <div class="card-body ">
                    <div class="form-group mt-3">
                        <label for="location-name" class="h5">Minimaal Aantal personen</label>
                        <input type="number" class="form-control" id="location-person-filter" name="minimum_person_filter">
                    </div>

                    <div class="form-group mt-3">
                        <label for="location-name" class="h5">Aantal zalen</label>
                        <input type="text" class="form-control" id="minimum-person-filter" name="minimum_person_filter">
                    </div>

                    <div class="form-group mt-3">
                        <label for="location-name" class="h5">Faciliteiten</label>
                        <?php
                        $field_name = 'location-facilities'; // Replace with the name or key of your ACF field
                        $field = get_field_object($field_name);

                        if ($field && isset($field['choices']) && is_array($field['choices'])) {
                            $checkbox_options = $field['choices'];

                            // Loop through the available checkbox options and create checkboxes
                            foreach ($checkbox_options as $value => $label) {
                                echo '<div class="form-check">';
                                echo '<input class="form-check-input location-facility-checkbox" type="checkbox" name="' . $field_name . '[]" value="' . esc_attr($value) . '" id="' . $field_name . '-' . esc_attr($value) . '">';
                                echo '<label class="form-check-label" for="' . $field_name . '-' . esc_attr($value) . '">' . esc_html($label) . '</label>';
                                echo '</div>';
                            }
                        } else {
                            echo 'No checkbox options found for the field.';
                        }
                        ?>
                    </div>
                </div>
            </div>
        </div>

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


                            echo 'ID - ' . get_the_ID() . ' ' . 'Distance - ' .  $distance . 'Selected Radius- ' . $selectedRadius;
                            echo '<br>';

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

                                // the_ID();
                ?>


                                <div class="card mb-5">
                                    <div class="card-body">
                                        <div class="row">
                                            <div class="col-md-7">
                                                <h2 class="card-title"><?php the_title(); ?></h2>
                                                <h5 class="font-weight-light"><?php the_field('alinea_1_titel'); ?></h5>
                                                <?php
                                                $locations_data = get_field('locations_data');
                                                ?>
                                                <div class="Locatiegegevens-item">
                                                    <p><i class="fas fa-location-pin"></i> <?php echo $locations_data['location_name_text']; ?></p>
                                                    <p><i class="fas fa-globe"></i> <?php echo  $locations_data['location_website_url']; ?></p>
                                                    <p><i class="fas fa-users"></i> <?php echo $locations_data['location_persons_number']; ?></p>
                                                    <p><i class="fas fa-building"></i> <?php echo $locations_data['location_rooms_number']; ?></p>
                                                </div>
                                                <!-- You can add other post-related information here -->
                                                <a href="<?php the_permalink(); ?>" class="btn btn-primary">Bekijk locatie</a>
                                            </div>
                                            <div class="col-md-5">
                                                <?php
                                                // Display the post thumbnail (featured image)
                                                if (has_post_thumbnail()) {
                                                    echo '<div class="card-img-top">';
                                                    the_post_thumbnail('large', ['class' => 'img-fluid location-feature-image']);
                                                    echo '</div>';
                                                }
                                                ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>


                            <?php }
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




                            ?>



                            <div class="card mb-5">
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-7">
                                            <h2 class="card-title"><?php the_title(); ?></h2>
                                            <h5 class="font-weight-light"><?php the_field('alinea_1_titel'); ?></h5>
                                            <?php
                                            $locations_data = get_field('locations_data');
                                            ?>
                                            <div class="Locatiegegevens-item">
                                                <p><i class="fas fa-location-pin"></i> <?php echo $locations_data['location_name_text']; ?></p>
                                                <p><i class="fas fa-globe"></i> <?php echo  $locations_data['location_website_url']; ?></p>
                                                <p><i class="fas fa-users"></i> <?php echo $locations_data['location_persons_number']; ?></p>
                                                <p><i class="fas fa-building"></i> <?php echo $locations_data['location_rooms_number']; ?></p>
                                            </div>
                                            <!-- You can add other post-related information here -->
                                            <a href="<?php the_permalink(); ?>" class="btn btn-primary">Bekijk locatie</a>
                                        </div>
                                        <div class="col-md-5">
                                            <?php
                                            // Display the post thumbnail (featured image)
                                            if (has_post_thumbnail()) {
                                                echo '<div class="card-img-top">';
                                                the_post_thumbnail('large', ['class' => 'img-fluid location-feature-image']);
                                                echo '</div>';
                                            }
                                            ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                <?php }
                    } else {
                        echo 'No posts found.';
                    }
                }
                ?>
            </div>
        </div>
    </div>
</div>

<?php
get_footer();

// Define the calculateDistance function here
