<?php

add_action('wp_ajax_update_custom_post_title', 'update_custom_post_title');

function update_custom_post_title()
{

    $location_post_title = sanitize_text_field($_POST['newLocationTitle']);
    $location_id = intval($_POST['locationId']); // Convert to integer for safety

    // Check if both values are not empty and the post ID is valid
    if (!empty($location_post_title) && $location_id > 0) {
        $post_data = array(
            'ID' => $location_id,
            'post_title' => $location_post_title,
        );

        // Update the post
        $result = wp_update_post($post_data);

        if (is_wp_error($result)) {
            // Error handling if wp_update_post fails
            echo 'Error updating post: ' . $result->get_error_message();
        } else {
            // Success message
            echo 'Post updated successfully';
        }
    } else {
        // Handle empty or invalid values
        echo 'Invalid data provided';
    }

    wp_die();
}

add_action('wp_ajax_update_location_contact_data', 'update_location_contact_data');

function update_location_contact_data()
{

    // Assuming you have already received the POST data
    $form_data_str = $_POST['formData'];
    $location_id = $_POST['locationId'];

    // Parse the URL-encoded string into an associative array
    parse_str($form_data_str, $form_data);

    // Get the existing $locations_data array
    $locations_data = get_field('locations_data', $location_id);

    // Update the fields in the $locations_data array
    $locations_data['location_name_text'] = sanitize_text_field($form_data['location_name']);
    $locations_data['location_website_url'] = esc_url($form_data['location_website']);
    $locations_data['location_persons_number'] = intval($form_data['location_person']);
    $locations_data['location_rooms_number'] = intval($form_data['location_rooms']);

    // Save the updated $locations_data array back to ACF
    update_field('locations_data', $locations_data, $location_id);


    wp_die();
}

add_action('wp_ajax_update_contact_data', 'update_contact_data');

function update_contact_data()
{
    echo 'worling';

    // Assuming you have already received the POST data
    $form_data_str = $_POST['formData'];
    $location_id = $_POST['locationId'];


    // Parse the URL-encoded string into an associative array
    parse_str($form_data_str, $form_data);

    // Get the existing $locations_data array
    $contacts_info = get_field('contacts_info', $location_id);

    // Update the fields in the $locations_data array
    $contacts_info['location_phone'] = sanitize_text_field($form_data['contact_phone']);
    $contacts_info['location_email'] = $form_data['contact_email'];

    // Save the updated $locations_data array back to ACF
    update_field('contacts_info', $contacts_info, $location_id);


    print_r($form_data);


    wp_die();
}

add_action('wp_ajax_update_facilities_field_options', 'update_facilities_field_options');

function update_facilities_field_options()
{
    $selected_values = isset($_POST['selected_values']) ? $_POST['selected_values'] : array();
    $post_id = $_POST['locationId']; // Replace this with the appropriate post ID


    // print_r($selected_values);


    // Update the ACF field object using the update_field function
    update_field('location-facilities',($selected_values), $post_id);
    // Return a response (if needed)
    echo json_encode(array('message' => 'ACF field updated successfully.'));

    wp_die();
}

add_action('wp_ajax_update_featured_image', 'update_featured_image');

function update_featured_image()
{
    $post_id = $_POST['post_id'];
    $image_id = $_POST['image_id'];

    // Check if an image was uploaded
    if (!empty($_FILES['image'])) {
        $uploaded_image = $_FILES['image'];

        // Check for upload errors
        if ($uploaded_image['error'] === 0) {
            // Handle the uploaded image and get its ID
            $image_id = media_handle_upload('image', $post_id);

            if (is_wp_error($image_id)) {
                wp_send_json_error(array('message' => 'Error handling uploaded image.'));
            } else {
                // Set the uploaded image as the featured image
                set_post_thumbnail($post_id, $image_id);
                wp_send_json_success();
            }
        } else {
            wp_send_json_error(array('message' => 'Error uploading image.'));
        }
    } else {
        wp_send_json_error(array('message' => 'No image file found.'));
    }
    wp_send_json_success();
}

function update_gallery_images()
{
    $post_id = $_POST['post_id']; // You may need to adjust this based on how you determine the post ID
    $gallery_images = $_POST['gallery_images'];

    // Update the ACF gallery field with the selected image IDs
    update_field('image_gallery', $gallery_images, $post_id); // Replace 'gallery_field_name' with your ACF field name

    // Return a success response
    wp_send_json_success();
}

add_action('wp_ajax_update_gallery_images', 'update_gallery_images');

function calculate_total()
{
    // Get the user input
    $total_amount = floatval($_POST['totalAmount']);
    $selectedPercentage = floatval($_POST['selectedPercentage']);

    // Calculate the total based on the selected percentage
    $total = $total_amount * ($selectedPercentage / 100);

    // Return the calculated total
    echo json_encode(array('total' => $total));
    wp_die(); // Always include this to terminate the script properly
}

// Hook your AJAX function to WordPress
add_action('wp_ajax_calculate_total', 'calculate_total');

add_action('wp_ajax_booking_form_submit', 'booking_form_submit');

function booking_form_submit()
{
    global $wpdb;

    $current_user = wp_get_current_user();

    $user_name = $current_user->user_login;
    $location_name = $_POST['locationName'];
    $user_total_amount = $_POST['locationUserTotal'];
    $planted_trees_total = $_POST['totalTrees'];
    $selected_date = $_POST['selectedDate'];

    print_r($location_name);

    $table_name = $wpdb->prefix . 'booking_options';
    $data = array(
        'location_name' => $location_name,
        'user_name' => $user_name,
        'total_amount' => ($user_total_amount),
        'total_trees' => intval($planted_trees_total),
        'event_date' => $selected_date,

    );

    // Get the table name
    $table_name = $wpdb->prefix . 'booking_options';

    $result = $wpdb->insert($table_name, $data);
    wp_die();
}




function custom_location_ajax_filter()
{
    if (isset($_POST['personQuantityValue'])) {
        $minimum_person_filter = intval($_POST['personQuantityValue']);

        // Check if $minimum_person_filter is empty or zero
        if (empty($minimum_person_filter) || $minimum_person_filter === 0) {
            // Perform the default query when personQuantityValue is empty
            $args = array(
                'post_type' => 'locations',
                'posts_per_page' => -1,
            );

            $custom_query = new WP_Query($args);

            ob_start();
            if ($custom_query->have_posts()) :
                while ($custom_query->have_posts()) :
                    $custom_query->the_post();
                    // Output location content here

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
                                    ?> </div>
                            </div>
                        </div>
                    </div>
    
                    <?php 
                endwhile;
                wp_reset_postdata();
            else :
                echo 'Sorry, geen locaties gevonden.';
            endif;
        } else {
            // Perform the custom query based on the provided minimum person quantity
            $args = array(
                'post_type' => 'locations',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key' => 'locations_data_location_persons_number',
                        'value' => $minimum_person_filter,
                        'compare' => '==',
                    ),
                ),
            );

            $custom_query = new WP_Query($args);

            ob_start();
            if ($custom_query->have_posts()) :
                while ($custom_query->have_posts()) :
                    $custom_query->the_post();
                    // Output location content here

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
                                    ?> </div>
                            </div>
                        </div>
                    </div>
    
                    <?php 
                endwhile;
                wp_reset_postdata();
            else :
                echo 'Sorry, geen locaties gevonden.';
            endif;
        }

        $output = ob_get_clean();
    } else {
        // If personQuantityValue is not set, perform the default query
        $args = array(
            'post_type' => 'locations',
            'posts_per_page' => -1,
        );

        $custom_query = new WP_Query($args);

        ob_start();
        if ($custom_query->have_posts()) :
            while ($custom_query->have_posts()) :
                $custom_query->the_post(); 
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
                                ?> </div>
                        </div>
                    </div>
                </div>

                <?php 

                
            endwhile;
            wp_reset_postdata();
        else :
            echo 'Sorry, geen locaties gevonden.';
        endif;
        $output = ob_get_clean();
    }

    echo $output;
    die();
}

add_action('wp_ajax_custom_location_filter', 'custom_location_ajax_filter');

function minimum_person_filter()
{
    if (isset($_POST['minimumPersonQuantityValue'])) {
        $minimum_person_filter = intval($_POST['minimumPersonQuantityValue']);

        if (empty($minimum_person_filter) || $minimum_person_filter === 0) {
            // If minimumPersonQuantityValue is empty or zero, perform the default query
            $default_args = array(
                'post_type' => 'locations',
                'posts_per_page' => -1,
            );

            $custom_query = new WP_Query($default_args);
        } else {
            // Perform the custom query based on the provided minimum person quantity
            $args = array(
                'post_type' => 'locations',
                'posts_per_page' => -1,
                'meta_query' => array(
                    array(
                        'key' => 'locations_data_location_rooms_number',
                        'value' => $minimum_person_filter,
                        'compare' => '==',
                    ),
                ),
            );

            $custom_query = new WP_Query($args);
        }

        ob_start();
        if ($custom_query->have_posts()) :
            while ($custom_query->have_posts()) :
                $custom_query->the_post();
                

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
                                ?> </div>
                        </div>
                    </div>
                </div>

                <?php 


            endwhile;
            wp_reset_postdata();
        else :
            echo 'Sorry, geen locaties gevonden.';
        endif;
        $output = ob_get_clean();
    } else {
        // If minimumPersonQuantityValue is not set, perform the default query
        $default_args = array(
            'post_type' => 'locations',
            'posts_per_page' => -1,
        );

        $custom_query = new WP_Query($default_args);

        ob_start();
        if ($custom_query->have_posts()) :
            while ($custom_query->have_posts()) :
                $custom_query->the_post();
                

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
                                ?> </div>
                        </div>
                    </div>
                </div>

                <?php 

            endwhile;
            wp_reset_postdata();
        else :
            echo 'Sorry, geen locaties gevonden.';
        endif;
        $output = ob_get_clean();
    }

    echo $output;
    die();
}

add_action('wp_ajax_minimum_person_filter', 'minimum_person_filter');



function custom_location_radius_ajax_filter() {
    if (isset($_POST['lat']) && isset($_POST['lng']) && isset($_POST['radius'])) {
        $latitude = sanitize_text_field($_POST['lat']);
        $longitude = sanitize_text_field($_POST['lng']);
        $radius = intval($_POST['radius']);

        // Perform the custom query and filtering based on latitude, longitude, and radius
        $args = array(
            'post_type' => 'locations', // Replace with your CPT slug
            'posts_per_page' => -1,
            // You may add additional query parameters as needed
        );

        // Add logic to filter posts based on the calculated radius
        // Implement your own logic to compare distances and filter posts within the radius

        $custom_query = new WP_Query($args);

        ob_start();
        if ($custom_query->have_posts()) :
            while ($custom_query->have_posts()) :
                $custom_query->the_post();
                // Output CPT posts as needed

                the_ID();
            endwhile;
            wp_reset_postdata();
        else :
            echo 'Sorry, geen locatie gevonden.';
        endif;
        $output = ob_get_clean();
    } else {
        $output = 'Sorry, geen locatie gevonden.';
    }

    echo $output;
    die();
}


add_action('wp_ajax_custom_location_radius_ajax_filter', 'custom_location_radius_ajax_filter');
add_action('wp_ajax_nopriv_custom_location_radius_ajax_filter', 'custom_location_radius_ajax_filter');






function filter_custom_facilities() {
    $facilities = isset($_POST['facilities']) ? $_POST['facilities'] : array();
    // Modify the query to filter by selected facilities (replace with your custom post type)
   
    // print_r($facilities);
   
    $args = array(
        'post_type' => 'locations',
        'posts_per_page' => -1,
        'meta_query' => array(
            'relation' => 'AND', // Use OR to include posts with any selected facility
        ),
    );

    foreach ($facilities as $facility) {
        $args['meta_query'][] = array(
            'key' => 'location-facilities', // Replace with your ACF field key
            'value' => $facility,
            'compare' => 'LIKE', // Use 'LIKE' for checkbox fields
        );
    }

    $custom_query = new WP_Query($args);


    // print_r($custom_query);

    ob_start();
    if ($custom_query->have_posts()) :
        while ($custom_query->have_posts()) :
            $custom_query->the_post();
            

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
                                ?> </div>
                        </div>
                    </div>
                </div>

                <?php 
        
        endwhile;
        wp_reset_postdata();
    else :
        echo 'Sorry, geen locatie gevonden.';
    endif;
    $output = ob_get_clean();

    echo $output;
    die();
}

add_action('wp_ajax_filter_custom_facilities', 'filter_custom_facilities');
// add_action('wp_ajax_nopriv_filter_custom_posts', 'filter_custom_posts');
