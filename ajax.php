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
    $form_data = $_POST['formData'];
    $location_id = $_POST['locationId'];

    // Convert the form data string into an associative array
    $form_data_arr = array();
    parse_str($form_data, $form_data_arr);
    $keys = array_keys($form_data_arr);
    // Get the existing ACF data
    $desc_location_data = get_field('Locatiegegevens', intval($location_id));
    $location_items = $desc_location_data['locatiegegevens_items'];

    // Iterate through the second array and update 'locatiegegevens_name' values
    foreach ($location_items  as $index => &$location_item) {
        if (isset($keys[$index])) {

            print_r($keys);
            $location_item['locatiegegevens_name'] = $form_data_arr[$keys[$index]];
        }
    }

    update_field('Locatiegegevens', array('locatiegegevens_items' =>$location_items ), $location_id);

    wp_die();
}
