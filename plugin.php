<?php

/**
 * Plugin Name: Green Booking Plugin
 * Plugin URI: https://github.com/mahmudhaisan/
 * Description: Booking form, Conditional Elements
 * Author: Mahmud haisan
 * Author URI: https://github.com/mahmudhaisan
 * Developer: Mahmud Haisan
 * Developer URI: https://github.com/mahmudhaisan
 * Text Domain: grbp
 * Domain Path: /languages
 * License: GNU General Public License v3.0
 * License URI: http://www.gnu.org/licenses/gpl-3.0.html
 */

if (!defined('ABSPATH')) {
    die('are you cheating');
}

define("GRBP_PLUGINS_PATH", plugin_dir_path(__FILE__));
define("GRBP_PLUGINS_DIR_URL", plugin_dir_url(__FILE__));

add_action('wp_enqueue_scripts', 'grbp_custom_enqueue_assets');

// Enqueue CSS and JavaScript
function grbp_custom_enqueue_assets()
{
    $api_key = 'AIzaSyDDGe4dCxn9exk8KpvF6Fr6bDh-rpvTJpw';


    //css
    wp_enqueue_style('bootstrap-min', plugin_dir_url(__FILE__) . 'assets/css/bootstrap.min.css');
    wp_enqueue_style('style-css-ss', plugin_dir_url(__FILE__) . 'assets/css/style.css');
    wp_enqueue_style('fontawesome-css-min', plugin_dir_url(__FILE__) . 'assets/css/fontawesome.min.css');

    //js
    wp_enqueue_script('bootstrap-min', plugin_dir_url(__FILE__) . 'assets/js/bootstrap.min.js', array('jquery'), '1.0.0', true);
    wp_enqueue_script('chartjs', plugin_dir_url(__FILE__) . 'assets/js/chart.min.js', array('jquery'), '3.7.0', true);
    // Replace 'YOUR_API_KEY' with your actual Google Maps API key


    // Enqueue the Google Maps Embed API script
    wp_enqueue_script('google-maps-embed-api', "https://maps.googleapis.com/maps/api/js?key={$api_key}&callback=initMap&libraries=places", array(), null, true);


    wp_enqueue_script('script', plugin_dir_url(__FILE__) . 'assets/js/script.js', array('jquery'), '1.0.0', true);
    wp_localize_script(
        'script',
        'greenbooking_plugin',
        array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        )
    );

    wp_enqueue_media();
}

add_action('admin_enqueue_scripts', 'grbp_custom_enqueue_admin_assets');

function grbp_custom_enqueue_admin_assets()
{
    // Enqueue Select2 styles and scripts for the admin area
    wp_enqueue_style('select2', plugin_dir_url(__FILE__) . 'assets/css/select2.min.css');
    wp_enqueue_style('dasboard-custom', plugin_dir_url(__FILE__) . 'assets/css/dashboard.css');

    // Enqueue your custom script that initializes Select2
    wp_enqueue_script('select2', plugin_dir_url(__FILE__) . 'assets/js/select2.min.js', array('jquery'), '4.1.0', true);
    wp_enqueue_script('custom-select2-init', plugin_dir_url(__FILE__) . 'assets/js/custom-select2-init.js', array('jquery', 'select2'), '1.0.0', true);
}

// Register Custom Post Type
function custom_post_type_locations()
{
    $labels = array(
        'name' => _x('Locaties', 'Post Type General Name', 'text_domain'),
        'singular_name' => _x('Location', 'Post Type Singular Name', 'text_domain'),
        'menu_name' => __('Locations', 'text_domain'),
        'name_admin_bar' => __('Location', 'text_domain'),
        'archives' => __('Location Archives', 'text_domain'),
        'attributes' => __('Location Attributes', 'text_domain'),
        'parent_item_colon' => __('Parent Location:', 'text_domain'),
        'all_items' => __('All Locations', 'text_domain'),
        'add_new_item' => __('Add New Location', 'text_domain'),
        'add_new' => __('Add New', 'text_domain'),
        'new_item' => __('New Location', 'text_domain'),
        'edit_item' => __('Edit Location', 'text_domain'),
        'update_item' => __('Update Location', 'text_domain'),
        'view_item' => __('View Location', 'text_domain'),
        'view_items' => __('View Locations', 'text_domain'),
        'search_items' => __('Search Location', 'text_domain'),
        'not_found' => __('Not found', 'text_domain'),
        'not_found_in_trash' => __('Not found in Trash', 'text_domain'),
        'featured_image' => __('Featured Image', 'text_domain'),
        'set_featured_image' => __('Set featured image', 'text_domain'),
        'remove_featured_image' => __('Remove featured image', 'text_domain'),
        'use_featured_image' => __('Use as featured image', 'text_domain'),
        'insert_into_item' => __('Insert into location', 'text_domain'),
        'uploaded_to_this_item' => __('Uploaded to this location', 'text_domain'),
        'items_list' => __('Locations list', 'text_domain'),
        'items_list_navigation' => __('Locations list navigation', 'text_domain'),
        'filter_items_list' => __('Filter locations list', 'text_domain'),
    );
    $args = array(
        'label' => __('Location', 'text_domain'),
        'description' => __('Post Type for locations', 'text_domain'),
        'labels' => $labels,
        'supports' => array('title', 'thumbnail', 'custom-fields'),
        'hierarchical' => false,
        'public' => true,
        'show_ui' => true,
        'show_in_menu' => true,
        'menu_position' => 5,
        'menu_icon' => 'dashicons-location',
        'show_in_admin_bar' => true,
        'show_in_nav_menus' => true,
        'can_export' => true,
        'has_archive' => true,
        'rewrite' => array('slug' => 'locations'),
        'capability_type' => 'post',
    );
    register_post_type('locations', $args);
}
add_action('init', 'custom_post_type_locations', 0);

add_filter('archive_template', 'create_archive_template');

function create_archive_template($archive_template)
{

    if (is_post_type_archive('locations')) {
        // Check if the template file exists in your plugin directory
        $archive_template = plugin_dir_path(__FILE__) . 'templates/archieve-locations.php';

        if (file_exists($archive_template)) {
            return $archive_template;
        }
    }
    return $archive_template;
}

function locations_single_template($template)
{



  
    // Get the current user's ID.
    $current_user_id = get_current_user_id();

    if (is_user_logged_in() && $current_user_id) {
        // Get the post ID for the current single CPT page.
        $post_id = get_the_ID();

        // Get the value of the 'location_access_user_id' post meta key for this post.
        $location_access_user_id = get_post_meta($post_id, 'location_access_user_id', true);

        if ($location_access_user_id && $current_user_id == $location_access_user_id && is_singular('locations')) {
            $template = plugin_dir_path(__FILE__) . 'templates/location-single-post-edit.php';
        } else {
            $template = plugin_dir_path(__FILE__) . 'templates/location-single-post.php';
        }
    } else {
        $template = plugin_dir_path(__FILE__) . 'templates/location-single-post.php';
    }


    return $template;
}
add_filter('single_template', 'locations_single_template');

// Add shortcode for booking form
function booking_form_cb()
{
    ob_start();
    $location_id = isset($_GET['location_id']) ? $_GET['location_id'] : '';
    $success_id = isset($_GET['success']) ? $_GET['success'] : '';

    if ($success_id == 'true') {
        echo '<h1 class="mt-5">Uw boeking is doorgegeven!</h1>';
        echo '<p class="mb-5">U kunt direct het aantal gespaarde bomen, en de details van de boeking, terugzien in uw omgeving. Klik hier om terug te keren naar uw omgeving.</p>';
    } else {

        if (is_user_logged_in()) {
            include GRBP_PLUGINS_PATH . '/template-parts/booking-form-html.php';
        } else {
            echo '<div class="mt-5 mb-5 h1"> U mag niet boeken. U moet manager zijn om te kunnen boeken</div>';
        }
    }

    return ob_get_clean();
}

add_shortcode('booking-form', 'booking_form_cb');


include GRBP_PLUGINS_PATH . '/functions.php';

if (is_admin() && defined('DOING_AJAX') && DOING_AJAX) {
    require GRBP_PLUGINS_PATH . '/ajax.php';
}

function green_booking_dashboard_menu()
{
    add_menu_page('Booking', 'Green Booking', 'manage_options', 'green-booking-settings', 'render_booking_options_page');
}
add_action('admin_menu', 'green_booking_dashboard_menu');

function create_booking_options_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . 'booking_options';

    $charset_collate = $wpdb->get_charset_collate();

    $sql = "CREATE TABLE $table_name (
        id mediumint(9) NOT NULL AUTO_INCREMENT,
        location_name varchar(255) NOT NULL,
        user_name varchar(255) NOT NULL,
        total_amount decimal(10, 2) NOT NULL,
        total_trees int(11) NOT NULL,
        event_date datetime NOT NULL,
        created_time datetime DEFAULT CURRENT_TIMESTAMP NOT NULL,
        PRIMARY KEY (id)
    ) $charset_collate;";

    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($sql);
}

register_activation_hook(__FILE__, 'create_booking_options_table');



add_shortcode('result-locaties-page', 'result_locaties_page_cb');

function result_locaties_page_cb()
{

    include GRBP_PLUGINS_PATH . '/views/results/results-locations.php';
}


add_shortcode('result-boekers-page', 'result_bookers_page_cb');

function result_bookers_page_cb()
{

    include GRBP_PLUGINS_PATH . '/views/results/results-bookers.php';
}


add_shortcode('locatie-login', 'locatie_login_cb');

function locatie_login_cb()
{


    include GRBP_PLUGINS_PATH . '/views/location/login.php';
}




add_shortcode('all-bookings-details', 'all_bookings_details_cb');

function all_bookings_details_cb()
{


    include GRBP_PLUGINS_PATH . '/views/bookings/all-bookings.php';
}
