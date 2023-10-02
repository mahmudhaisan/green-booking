<?php
get_header(); // Include your header template


$location_post_id = get_the_ID();
$locations_data = get_field('locations_data');
$contacts_info = get_field('contacts_info');

?>

<div class="container mt-3 mb-5">
    <div class="row">
        <div class="col-md-12">
            <input id="location-post-id" type="hidden" value="<?php echo $location_post_id; ?>">
            <?php custom_breadcrumbs(); // Include your custom breadcrumb function

            ?>
        </div>
    </div>

    <div class="row image-gallery-grid align-items-center">
        <div class="col-md-12">
            <h1 class="d-inline"><?php the_title(); ?></h1>
            <button class="btn d-inline btn-lg" id="location-title-edit" location-post-id="<?php echo $location_post_id; ?>">
                <i class="fas fa-edit"></i>
            </button>
        </div>



        <!-- Modal -->
        <div class="modal fade" id="locationEditModal" tabindex="-1" role="dialog" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Location Title</h5>
                        <button type="button" class="btn btn-primary" id="location-title-save-changes">Save changes</button>

                    </div>
                    <div class="modal-body">
                        <!-- Add your form elements for editing the location title here -->
                        <input type="text" class="form-control" id="location-title-input" placeholder="Enter new title">
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <div class="row">
        <div class="col-md-7 image-feature-left">
            <?php
            if (has_post_thumbnail()) {
                the_post_thumbnail('large', ['class' => 'img-fluid']);
            }
            ?>
            <button id="change-featured-image" class="mt-3 bg-primary border-0">Change Featured Image</button>

        </div>
        <div class="col-md-5">
            <?php
            echo '<div class="row">';
            // Retrieve and display ACF images here
            $acf_images = get_field('image_gallery');
            if ($acf_images && is_array($acf_images)) {
                foreach ($acf_images as $acf_image) {
                    echo '<div class="col-md-6 single-location-gallery">';
                    echo '<img src="' . esc_url($acf_image['url']) . '" alt="' . esc_attr($acf_image['alt']) . '" class="img-fluid mb-3">';
                    echo '</div>';
                }
            }
            echo '</div>';
            ?>
            <button id="change-gallery" class="bg-primary border-0">Change Featured Image</button>




        </div>




    </div>

    <?php

    $title1_object = get_field_object('alinea_1_titel'); // Replace 'field_name' with your ACF field's name
    $title1_label = $title1_object['label'];
    $title1_value = $title1_object['value'];
    $desc1_object = get_field_object('alinea_1_tekst'); // Replace 'field_name' with your ACF field's name
    $desc1_label = $desc1_object['label'];
    $desc1_value = $desc1_object['value'];

    $title2_object = get_field_object('alinea_2_titel'); // Replace 'field_name' with your ACF field's name
    $title2_label = $title2_object['label'];
    $title2_value = $title2_object['value'];
    $desc2_object = get_field_object('alinea_2_tekst'); // Replace 'field_name' with your ACF field's name
    $desc2_label = $desc2_object['label'];
    $desc2_value = $desc2_object['value'];

    ?>

    <div class="row mt-5">
        <div class="col-md-7">
            <div class="title-desc-1">
                <div class="location-desc-label">
                    <h3 class="location-text">Locatie omschrijving</h3>
                </div>
                <h4 class="location-title-label"><?php echo $title1_label; ?></h4>
                <p class="location-title-val"><?php echo $title1_value; ?></p>
            </div>
            <div class="location-desc">
                <h4 class="location-desc-label"><?php echo $desc1_label; ?></h4>
                <p class="location-desc-val"><?php echo $desc1_value; ?></p>
            </div>


            <div class="title-desc-2">
                <div class="location-title">
                    <h4 class="location-title-label"><?php echo $title2_label; ?></h4>
                    <p class="location-title-val"><?php echo $title2_value; ?></p>
                </div>
                <div class="location-desc">
                    <h4 class="location-desc-label"><?php echo $desc2_label; ?></h4>
                    <p class="location-desc-val"><?php echo $desc2_value; ?></p>
                </div>
            </div>


            <div class="faciliteiten-item mt-5">
                <div class="faciliteiten-headerm mt-3 mb-3">
                    <div class="row align-items-center">
                        <div class="col-md-9">
                            <h3>Faciliteiten</h3>
                            <p class=""> Faciliteiten Selecteer hier wat uw locatie te bieden heeft.</p>
                        </div>
                        <div class="col-md-3">
                            <!-- Button to trigger the modal -->
                            <button class="btn d-inline btn-lg" id="facilities-data-edit">
                                Edit <i class="fas fa-edit"></i>
                            </button>

                            <!-- Modal -->
                            <div class="modal fade" id="facilitiesEditModal" tabindex="-1" role="dialog" aria-labelledby="facilitiesEditModalLabel" aria-hidden="true">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <form action="" method="post">


                                            <div class="modal-header">
                                                <h5 class="modal-title" id="facilitiesEditModalLabel">Edit Facilities Data</h5>
                                                <button type="button" class="btn btn-primary" id="saveFacilities">Save Changes</button>
                                            </div>

                                            <div class="modal-body">
                                                <?php
                                                // Retrieve and display the available choices from your ACF field
                                                $field = get_field_object('location-facilities');
                                                if ($field['type'] === 'checkbox') {
                                                    $choices = $field['choices'];
                                                    $selected_values = get_field('location-facilities'); // Get the selected values




                                                    foreach ($choices as $value) {



                                                        echo '<div class="form-check">';
                                                        echo '<input type="checkbox" class="form-check-input" id="' . esc_attr($value) . '" value="' . esc_attr($value) . '"';

                                                        // Check if the current option is selected
                                                        $isOptionSelected = false;
                                                        if (is_array($selected_values)) {
                                                            foreach ($selected_values as $selectedOption) {
                                                                if ($selectedOption['value'] === $value) {
                                                                    $isOptionSelected = true;
                                                                    break; // Exit the loop if the option is found
                                                                }
                                                            }
                                                        }

                                                        if ($isOptionSelected) {
                                                            echo ' checked="checked"';
                                                        }


                                                        echo '>';
                                                        echo '<label class="form-check-label" for="' . esc_attr($value) . '">' . ($value) . '</label>';
                                                        echo '</div>';
                                                    }
                                                }
                                                ?>
                                            </div>



                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-secondary close" data-dismiss="modal">Close</button>

                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>


                <div class="">


                    <?php
                    $faciliteiten_items = get_field('location-facilities');

                    // print_r($faciliteiten_items);
                    // Define a generic mapping of labels to Font Awesome icons
                    $generic_mapping = [
                        'Geluidsinstallatie' => 'fa-microphone',
                        'Extra schermen/plug & play' => 'fa-desktop',
                        'Catering' => 'fa-utensils',
                        'Bar' => 'fa-wine-glass',
                        'Restaurant' => 'fa-utensils', // Example mapping, you can change this
                        'Invalidetoegankelijk' => 'fa-wheelchair',
                        'Overnachten' => 'fa-bed',
                        'VIP (hele locatie huren)' => 'fa-crown',
                        'Gratis parkeren' => 'fa-car',
                        'Vlakbij OV' => 'fa-bus',
                        'EV laadpaal' => 'fa-charging-station',
                        // Add more mappings as needed
                    ];


                    if (!empty($faciliteiten_items)) {
                        echo '<div class="row">';
                        $count = 0;

                        foreach ($faciliteiten_items as $item) {
                            $faciliteiten_item_value = $item['value'];

                            if ($count % 2 == 0) {
                                // Start a new row
                                echo '</div><div class="row">';
                            }

                            echo '<div class="col-md-6">';
                            echo '<ul class="list-unstyled mb-3">';

                            echo '<li>';

                            // Display the Font Awesome icon based on the label
                            if (isset($generic_mapping[$faciliteiten_item_value])) {
                                $icon_class = $generic_mapping[$faciliteiten_item_value];
                                echo '<i class="fas ' . $icon_class . '"></i> ';
                            }

                            // Display the facility label
                            if ($faciliteiten_item_value) {
                                echo $faciliteiten_item_value;
                            }

                            echo '</li>';

                            echo '</ul>';
                            echo '</div>';
                            $count++;
                        }

                        echo '</div>';
                    }
                    ?>

                </div>

            </div>
        </div>

        <div class="col-md-5 ">
            <div class="right-side-location-contact bg-primary text-white p-4">

                <div class="locatiion-header">
                    <h3 class="d-inline">Locatiegegevens </h3>
                    <!-- Button to trigger the modal -->
                    <button class="btn d-inline btn-primary border-0 btn-lg" id="location-data">
                        Edit <i class="fas fa-edit"></i>
                    </button>

                    <!-- Modal -->
                    <div class="modal fade" id="locationContactEdit" tabindex="-1" role="dialog" aria-labelledby="locationEditModalLabel" aria-hidden="true">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">

                                <div class="modal-body">

                                    <form method="POST" action="">
                                        <div class="modal-header">
                                            <h5 class="modal-title text-dark" id="locationEditModalLabel">Edit Location and Contact Information</h5>
                                            <input type="submit" name="update_contact_names" value="Save Changes" class="btn btn-primary">
                                        </div>
                                        <!-- Location Form Fields -->
                                        <div class="form-group">
                                            <label for="location-name">Location Name:</label>
                                            <input type="text" class="form-control" id="location-name" name="location_name" placeholder="Enter location name" value="<?php echo esc_attr($locations_data['location_name_text']); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="location-website">Website:</label>
                                            <input type="text" class="form-control" id="location-website" name="location_website" placeholder="Enter Website" value="<?php echo esc_attr($locations_data['location_website_url']); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="location-person">Location Person:</label>
                                            <input type="text" class="form-control" id="location-person" name="location_person" placeholder="Enter Location Person" value="<?php echo esc_attr($locations_data['location_persons_number']); ?>">
                                        </div>

                                        <div class="form-group">
                                            <label for="location-rooms">Location Rooms:</label>
                                            <input type="text" class="form-control" id="location-rooms" name="location_rooms" placeholder="Enter Location Rooms" value="<?php echo esc_attr($locations_data['location_rooms_number']); ?>">
                                        </div>

                                    </form>

                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        Close
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                </div>

                <div class="Locatiegegevens-item">

                    <?php




                    ?>
                    <p class="location-name-icon"><i class="fas fa-location-pin"></i> <?php echo ' '.  $locations_data['location_name_text']; ?></p>
                    <p class="location-name-icon"><i class="fas fa-globe"></i> <?php echo ' '.  $locations_data['location_website_url']; ?></p>
                    <p class="location-name-icon"><i class="fas fa-users"></i> <?php echo ' '.  $locations_data['location_persons_number']; ?></p>
                    <p class="location-name-icon"> <i class="fas fa-building"></i> <?php echo ' '. $locations_data['location_rooms_number']; ?></p>



                </div>

                <div class="">

                    <div class="contact-header">
                        <h3 class="d-inline">Contactgegevens </h3>
                        <!-- Button to trigger the modal -->
                        <button class="btn d-inline btn-primary border-0 btn-lg" id="contact-data">
                            Edit <i class="fas fa-edit"></i>
                        </button>

                        <!-- Modal -->
                        <div class="modal fade" id="contactEdit" tabindex="-1" role="dialog" aria-labelledby="locationEditModalLabel" aria-hidden="true">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">

                                    <div class="modal-body">

                                        <form method="POST" action="">
                                            <div class="modal-header">
                                                <h5 class="modal-title text-dark" id="locationEditModalLabel">Edit Contact Info's</h5>
                                                <input type="submit" name="update_location_names" value="Save Changes" class="btn btn-primary">
                                            </div>


                                            <!-- Contact Form Fields -->

                                            <div class="form-group">
                                                <label for="contact-phone">Phone Number:</label>
                                                <input type="tel" class="form-control" id="contact-phone" name="contact_phone" value="<?php echo $contacts_info['location_phone']; ?>" placeholder="Enter phone number">
                                            </div>

                                            <div class="form-group">
                                                <label for="contact-email">Email:</label>
                                                <input type="email" class="form-control" id="contact-email" name="contact_email" value="<?php echo $contacts_info['location_email']; ?>" placeholder="Enter email">
                                            </div>


                                        </form>



                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                            Close
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>



                    </div>

                    <div class="Contactgegevens-item">

                        <?php


                        $contacts_info = get_field('contacts_info');

                        ?>
                        <p><i class="fas fa-phone"></i> <?php echo $contacts_info['location_phone']; ?></p>
                        <p><i class="fas fa-envelope"></i> <?php echo $contacts_info['location_email']; ?></p>

                    </div>
                </div>

                <!-- You can add other post-related information here -->
                <a href="<?php the_permalink(); ?>" class="btn btn-primary">Bekijk locatie</a>
            </div>

            <div class="book-now-for-manager-section">
                <div class="p-3">
                    <a href="<?php echo get_home_url(); ?>/booking/?location_id=<?php the_ID(); ?>" class="btn btn-primary text-white h2">Book Now</a>
                </div>
            </div>
        </div>
    </div>


</div>



<?php
get_footer(); // Include your footer template
?>