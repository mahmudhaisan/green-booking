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
    //css
    wp_enqueue_style('bootstrap-min', plugin_dir_url(__FILE__) . 'assets/css/bootstrap.min.css');
    wp_enqueue_style('style-css-ss', plugin_dir_url(__FILE__) . 'assets/css/style.css');
    wp_enqueue_style('fontawesome-css-min', plugin_dir_url(__FILE__) . 'assets/css/fontawesome.min.css');

    //js
    wp_enqueue_script('bootstrap-min', plugin_dir_url(__FILE__) . 'assets/js/bootstrap.min.js', array('jquery'), '1.0.0', true);

    wp_enqueue_script('script', plugin_dir_url(__FILE__) . 'assets/js/script.js', array('jquery'), '1.0.0', true);
    wp_localize_script(
        'script',
        'greenbooking_plugin',
        array(
            'ajaxurl' => admin_url('admin-ajax.php'),
        )
    );
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
        'name' => _x('Locations', 'Post Type General Name', 'text_domain'),
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

    $manager_accees_object = get_field_object('manager-edit-access');
    if ($manager_accees_object) {
        $manager_accees_value = $manager_accees_object['value'];
    }

    if ($manager_accees_value == 'Yes' &&  is_singular('locations')) {
            $template = plugin_dir_path(__FILE__) . 'templates/location-single-post-edit.php';
    }else{
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
    if ($location_id) {
        include GRBP_PLUGINS_PATH . '/template-parts/booking-form-html.php';
    } else {
        return 'Error: Location parameter does not equal "wp"';
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
    add_menu_page('Settings', 'Green Booking', 'manage_options', 'green-booking-settings', 'green_booking_settings_cb');
}
add_action('admin_menu', 'green_booking_dashboard_menu');

function green_booking_settings_cb()
{
    ?>
    <div class="wrap">
        <h1>Welcome to green booking settings</h1>

    <?php
}


