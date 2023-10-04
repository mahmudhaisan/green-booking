<?php
function custom_breadcrumbs()
{
    // Home page URL
    $home_url = get_bloginfo('url');

    // Separator between breadcrumbs
    $separator = ' / ';

    // Start the breadcrumbs
    $breadcrumbs = '<nav aria-label="breadcrumb"><ol class="breadcrumb">';

    // Home breadcrumb
    $breadcrumbs .= '<li class="breadcrumb-item"><a href="' . esc_url($home_url) . '">Startpagina</a></li>';

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

if (!class_exists('WP_List_Table')) {
    require_once ABSPATH . 'wp-admin/includes/class-wp-list-table.php';
}

class Booking_Options_List_Table extends WP_List_Table
{
    public function __construct()
    {
        parent::__construct([
            'singular' => 'booking_option',
            'plural' => 'booking_options',
            'ajax' => false,
        ]);
    }

    public function get_columns()
    {
        return [
            'cb' => '<input type="checkbox" />',
            'location_name' => 'Locatie naam',
            'user_name' => 'Gebruikersnaam',
            'total_amount' => 'Totaalbedrag',
            'total_trees' => 'Geplante bomen',
            'event_date' => 'Datum'


        ];
    }

    public function prepare_items()
    {
        $data = $this->get_data(); // Implement this method to retrieve your data from the custom table
        $this->_column_headers = [$this->get_columns(), [], []];
        $this->items = $data;
        $this->process_bulk_action();




        // Define pagination arguments
        $per_page = 10; // Number of items per page
        $current_page = $this->get_pagenum();
        $total_items = count($data);

        // Create pagination
        $this->set_pagination_args([
            'total_items' => $total_items,
            'per_page'    => $per_page,
        ]);

        // Slice the data to display the current page's items
        $this->items = array_slice($data, (($current_page - 1) * $per_page), $per_page);
    }

    public function column_default($item, $column_name)
    {
        return $item[$column_name];
    }

    public function column_cb($item)
    {
        return sprintf(
            '<input type="checkbox" name="id[]" value="%s" />',
            $item['id']
        );
    }

    public function get_bulk_actions()
    {
        $actions = [
            'delete' => 'Delete',
            // Add more bulk actions as needed
        ];
        return $actions;
    }

    public function process_bulk_action()
    {
        if ('delete' === $this->current_action()) {

            echo 'working';
            $ids = isset($_POST['id']) ? $_POST['id'] : array();

            if (!empty($ids)) {
                global $wpdb;
                $table_name = $wpdb->prefix . 'booking_options';

                foreach ($ids as $id) {
                    // Implement your deletion logic here
                    $wpdb->delete($table_name, array('id' => $id), array('%d'));
                }

                // Get the current page's URL
                $current_url = add_query_arg($_SERVER['QUERY_STRING'], '', get_permalink());

                // Redirect to the current page, effectively reloading it
                wp_redirect($current_url);
                exit;
            }
        }
    }

    private function get_data()
    {
        global $wpdb;
        $table_name = $wpdb->prefix . 'booking_options';

        $data = $wpdb->get_results("SELECT * FROM $table_name ORDER BY id DESC", ARRAY_A);
        return $data;
    }
}

function render_booking_options_page()
{
    $myListTable = new Booking_Options_List_Table();
    $myListTable->prepare_items();

    echo '<form method="post">';
    echo '<div class="wrap"><h2>Boekingslijsten</h2>';
    $myListTable->display();
    echo '</div>';
    echo '</form>';
}

function redirect_logged_in_users()
{
    if (is_user_logged_in() && is_page('login')) { // Check if the user is logged in and on the login page
        wp_redirect(home_url()); // Redirect to the home page
        exit;
    }
}
add_action('template_redirect', 'redirect_logged_in_users');


function add_manager_role()
{
    add_role('manager', 'Manager', [
        'read' => true,
    ]);
}
add_action('init', 'add_manager_role');

// Remove the Shop Manager role
function remove_shop_manager_role()
{
    remove_role('shop_manager');
}
add_action('init', 'remove_shop_manager_role');


function custom_login_redirect($redirect_to, $request, $user)
{
    // Check if the user is an admin and redirect to the admin dashboard

    if (is_array($user->roles) && in_array('administrator', $user->roles)) {
        return admin_url();
    } else {
        // Redirect other users to the home page
        return home_url();
    }
}


add_filter('login_redirect', 'custom_login_redirect', 10, 3);


function update_username_and_password_based_on_acf_fields($post_id)
{
    // Check if this is a "Locations" post type
    if (get_post_type($post_id) !== 'locations') {
        return;
    }

    // Get the submitted username and password fields from ACF
    $new_username = sanitize_text_field(get_field('location_user_access_info_location_access_user_name', $post_id));
    $new_password = get_field('location_user_access_info_location_access_user_password', $post_id);

    // Check if the username field is empty
    if (empty($new_username)) {
        return;
    }



}

add_action('acf/save_post', 'update_username_and_password_based_on_acf_fields');



function create_update_user_from_acf($post_id) {
    // Check if this is a "Locations" post type
    if (get_post_type($post_id) !== 'locations') {
        return;
    }

    // Get the submitted username and password fields from ACF
    $username = sanitize_text_field(get_field('location_user_access_info_location_access_user_name', $post_id));
    $password = get_field('location_user_access_info_location_access_user_password', $post_id);

    // Check if the user already exists.
    $user = get_user_by('login', $username);

    if ($user === false) {
        // User doesn't exist, create a new user.
        $user_id = wp_create_user($username, $password);
    } else {
        // User exists, update the username and password.
        $user_id = $user->ID;
        wp_update_user(array('ID' => $user_id, 'user_login' => $username, 'user_pass' => $password));
    }

    update_post_meta($post_id, 'location_access_user_id', $user_id);

}

// Hook into the ACF save_post action.
add_action('acf/save_post', 'create_update_user_from_acf');








function my_acf_google_map_api_key($api) {
    // Replace 'YOUR_API_KEY' with your actual Google Maps API key
    $api['key'] = 'AIzaSyDDGe4dCxn9exk8KpvF6Fr6bDh-rpvTJpw';
    return $api;
}
add_filter('acf/fields/google_map/api', 'my_acf_google_map_api_key');


add_action('acf/save_post', 'update_map_lat_lng_location');


function update_map_lat_lng_location($post_id) {
    // Check if the post ID is valid
    if (!$post_id || !is_numeric($post_id)) {
        return; // Post ID is not valid, exit the function
    }

    // Check if the ACF field 'google_map_location' exists and has lat and lng values
    $location_data = get_field('google_map_location', $post_id);




    if (!$location_data || !isset($location_data['lat']) || !isset($location_data['lng'])) {
        return; // Location data is missing or incomplete, exit the function
    }



    $location_lat = $location_data['lat'];
    $location_lng = $location_data['lng'];

    // Make sure lat and lng values are valid numbers
    if (!is_numeric($location_lat) || !is_numeric($location_lng)) {
        return; // Invalid latitude or longitude values, exit the function
    }

    // Round the latitude and longitude to 7 decimal places
    $location_lat_round = round(floatval($location_lat), 7);
    $location_lng_round = round(floatval($location_lng), 7);

    // Update post meta fields with the rounded values
    update_post_meta($post_id, 'location_lat', $location_lat_round);
    update_post_meta($post_id, 'location_lng', $location_lng_round);
}

// Hook this function to an appropriate action or filter, e.g., 'save_post'

function calculateDistance($lat1, $lng1, $lat2, $lng2)
{
    // Radius of the Earth in kilometers
    $earthRadius = 6371;

    // Convert latitude and longitude from degrees to radians
    $lat1 = deg2rad($lat1);
    $lng1 = deg2rad($lng1);
    $lat2 = deg2rad($lat2);
    $lng2 = deg2rad($lng2);

    // Haversine formula
    $dLat = $lat2 - $lat1;
    $dLng = $lng2 - $lng1;
    $a = sin($dLat / 2) * sin($dLat / 2) + cos($lat1) * cos($lat2) * sin($dLng / 2) * sin($dLng / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    $distance = $earthRadius * $c;

    return $distance;
}

function calculateTreeStatistics() {
    global $wpdb;

    $locations_count = wp_count_posts('locations')->publish;
    $table_name = $wpdb->prefix . 'booking_options';
    $currentDateTime = new DateTime();
    $currentYear = $currentDateTime->format('Y');

    $query = "SELECT * FROM $table_name";
    $results = $wpdb->get_results($query);

    $plantedTreesThisWeek = 0;
    $plantedTreesThisYear = 0;
    $plantedTreesSinceStart = 0;

    if (!empty($results)) {
        foreach ($results as $result) {
            $createdTime = new DateTime($result->created_time);
            $diff = $currentDateTime->diff($createdTime);

            if ($diff->days <= 7 && $createdTime->format('N') <= $currentDateTime->format('N')) {
                $plantedTreesThisWeek += $result->total_trees;
            }

            if ($createdTime->format('Y') == $currentYear) {
                $plantedTreesThisYear += $result->total_trees;
            }

            $plantedTreesSinceStart += $result->total_trees;
        }
    }



    return array(
        'locations_count' => $locations_count,
        'planted_trees_this_week' => $plantedTreesThisWeek,
        'planted_trees_this_year' => $plantedTreesThisYear,
        'planted_trees_since_start' => $plantedTreesSinceStart,
    );
}