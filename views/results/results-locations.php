<?php

echo '<br><br>';

echo custom_breadcrumbs();

?>


<div class="h1"> Resultaten</div>

<div class="row">

    <div class="col-md-7 ">
        <div class="chart-part">
            <div class="form-group">
                <label for="yearDropdown" class="col-form-label">Selecteer jaar:</label>
                <div class="">
                    <select id="yearDropdown" class="form-select">
                        <!-- Options go here -->
                    </select>
                </div>
            </div>

            <div class="">
                <canvas id="lineChart" width="600" height="400"></canvas>
            </div>

        </div>
    </div>

    <div class="col-md-5 mb-5">
        <div class="card stats-card bg-primary d-flex flex-column justify-content-center align-items-center p-4 ml-5 mr-5">
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-md-6 mb-3">
                        <div class="d-flex flex-column align-items-center mb-4">
                            <i class="far fa-calendar fa-2x text-warning"></i>
                            <h3 class="h1 mt-2">8</h3>
                            <p class="">Boekingen  <br> deze maand</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex flex-column align-items-center mb-4">
                            <i class="far fa-calendar-alt fa-2x text-warning"></i>
                            <p class="h1 mt-2">8</p>
                            <p class="">Geplante  <br> bomen deze week</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex flex-column align-items-center mb-4">
                            <i class="far fa-calendar-check fa-2x text-warning"></i>
                            <p class="h1 mt-2">8</p>
                            <p class="">Boekingen <br> dit jaar</p>
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex flex-column align-items-center mb-4">
                            <i class="far fa-calendar-check fa-2x text-warning"></i>
                            <p class="h1 mt-2">8</p>
                            <p class="">Geplante <br>
                                bomen sinds start</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="col-md-12 mt-5 mb-5">
        <div class="row">
            <div class="col-md-8">
                <p class="">Ontdek alle</p>
                <h3 class="mt-1">Aangesloten locaties</h3>
            </div>
            <div class="col-md-4">
                <!-- Optional content for the right column -->
            </div>
        </div>
    </div>

    <div class="col-md-12">
        <div class="row">
            <?php
            // Query the latest location post
            $args = array(
                'post_type' => 'locations',
                'posts_per_page' => 6, // Limit to 6 items
                'order' => 'DESC',
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
            ?>
                    <div class="col-md-4">
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


</div>