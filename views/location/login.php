

<div class="container mt-5 mb-3">
    <div class="row">

        <div class="col-md-8">
            <div class="row align-items-center bg-light p-2 rounded shadow">
                <div class="col-md-9">
                    <h1 class="">Boeking doorgeven</h1>
                    <p class="">Geef hier uw nieuwe boekingen door </p>
                </div>
                <div class="col-md-3">
                    <a href="https://greenbookings.nl/booking/" class="btn btn-primary p-3 rounded"> Nu doorgeven</a>
                </div>
            </div>

            <div class="row mt-5 mb-5">
                <div class="location-panted-tress-info bg-light p-2 rounded shadow">
                    <table class="table">
                        <thead class="bg-primary p-3">
                            <tr>
                                <th>Datum</th>
                                <th>Boeker</th>
                                <th>GESPAARDE BOMEN</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            global $wpdb;
                            $table_name = $wpdb->prefix . 'booking_options';

                            // Get the current user's username or identifier, depending on how it's stored
                            $current_user_id = get_current_user_id(); // Replace with your method to get the current user's name

                            // Query to retrieve the latest 10 records for the current user ordered by event date
                            $query = "SELECT event_date, user_name, total_trees FROM $table_name WHERE ID = $current_user_id ORDER BY event_date DESC LIMIT 10";

                            $results = $wpdb->get_results($query);

                            // Check if there are results
                            if ($results) {
                                foreach ($results as $result) {
                                    echo '<tr>';
                                    echo '<td>' . date('Y-m-d', strtotime($result->event_date)) . '</td>';
                                    echo '<td>' . $result->user_name . '</td>';
                                    echo '<td>' . $result->total_trees . '</td>';
                                    echo '</tr>';
                                }
                            } else {
                                echo '<tr><td colspan="3">Er zijn geen records gevonden voor de huidige gebruiker.</td></tr>';
                            }

                            ?>

                        </tbody>
                    </table>

                    <a href="https://greenbookings.nl/informatie-over-locatiedetails/" class="btn btn-primary p-2 justify-content-end">Bekijk alle boekingen</a>


                </div>
            </div>

        </div>

        <div class="col-md-4">
            <?php

            $current_user_id = get_current_user_id();

            // Query the latest location post
            $args = array(
                'post_type'      => 'locations',
                'posts_per_page' => 1,
                'order'          => 'DESC',
                'meta_query'     => array(
                    array(
                        'key'     => 'location_access_user_id',
                        'value'   => $current_user_id,
                        'compare' => '=',
                    ),
                ),
            );

            $latest_location_query = new WP_Query($args);

            if ($latest_location_query->have_posts()) :
                while ($latest_location_query->have_posts()) :
                    $latest_location_query->the_post();

                    // Retrieve ACF values
                    $location_title = get_the_title();
                    $location_description = get_field('location_description'); // Replace with your ACF field name

                    // Retrieve the featured image
                    $location_image_url = get_the_post_thumbnail_url(get_the_ID()); // 'thumbnail' is the image size, you can change it to any registered image size

                    // Display Bootstrap card
            ?>



                    <div class="card">
                        <img class="latest-location-image" src="<?php echo esc_url($location_image_url); ?>" alt="Location Image">

                        <div class="card-body">
                            <h5 class="card-title"><?php echo esc_html($location_title); ?></h5>

                            <?php

                            $locations_data = get_field('locations_data');

                            ?>
                            <p><i class="fas fa-globe"></i> <?php echo $locations_data['location_name_text']; ?></p>
                            <p><i class="fas fa-link"></i> <?php echo $locations_data['location_website_url']; ?></p>

                            <a href="<?php the_permalink(); ?>" class="btn btn-primary">Locatiegegevens bewerken</a>
                        </div>
                    </div>
        </div>



<?php
                endwhile;
                wp_reset_postdata();
            else :
                echo 'No locations found.';
            endif;
?>

    </div>
</div>