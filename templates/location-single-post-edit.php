<?php
get_header(); // Include your header template


$location_post_id = get_the_ID();


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


            <div class="faciliteiten-item ">
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
                                        <div class="modal-header">
                                            <h5 class="modal-title" id="facilitiesEditModalLabel">Edit Facilities Data</h5>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input" value="Option 1"> Option 1
                                                    </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input" value="Option 2"> Option 2
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input" value="Option 3"> Option 3
                                                    </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input" value="Option 4"> Option 4
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input" value="Option 5"> Option 5
                                                    </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input" value="Option 6"> Option 6
                                                    </label>
                                                </div>
                                            </div>
                                            <div class="row">
                                                <div class="col-md-6">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input" value="Option 7"> Option 7
                                                    </label>
                                                </div>
                                                <div class="col-md-6">
                                                    <label class="form-check-label">
                                                        <input type="checkbox" class="form-check-input" value="Option 8"> Option 8
                                                    </label>
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                            <button type="button" class="btn btn-primary">Save Changes</button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                        </div>
                    </div>
                </div>


                <?php

                $faciliteiten_data = get_field('location-facilities');
                $faciliteiten_items = get_field('location-facilities');


                if (!empty($faciliteiten_items)) {
                    echo '<div class="row">';
                    $count = 0;

                    foreach ($faciliteiten_items as $item) {
                        $faciliteiten_item_value = $item['value'];
                        $faciliteiten_item_label = $item['label'];


                        if ($count % 2 == 0) {
                            // Start a new row
                            echo '</div><div class="row">';
                        }

                        echo '<div class="col-md-6">';
                        echo '<ul class="list-unstyled mb-3">';

                        echo '<li>';

                        // Display the image on the left
                        if ($faciliteiten_item_value) {
                            echo $faciliteiten_item_value;
                        }

                        echo ' ';

                        // Display the other field value on the right
                        if ($faciliteiten_item_label) {
                            echo $faciliteiten_item_label;
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

        <div class="col-md-5 ">
            <div class="right-side-location-contact bg-success text-white p-3">

                <div class="locatiion-header">
                    <h3 class="d-inline">Locatiegegevens </h3>
                    <!-- Button to trigger the modal -->
                    <button class="btn d-inline btn-success btn-lg" id="location-data">
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
                                            <input type="text" class="form-control" id="location-name" name="location_name" placeholder="Enter location name">
                                        </div>

                                        <div class="form-group">
                                            <label for="location-address">Website:</label>
                                            <input type="text" class="form-control" id="location-website" name="location_website" placeholder="Enter Website">
                                        </div>

                                        <div class="form-group">
                                            <label for="location-person">Location Person:</label>
                                            <input type="text" class="form-control" id="location-person" name="location_person" placeholder="Enter Location Person">
                                        </div>

                                        <div class="form-group">
                                            <label for="location-rooms">Location Rooms:</label>
                                            <input type="text" class="form-control" id="location-rooms" name="location_rooms" placeholder="Enter Location Rooms">
                                        </div>

                                        <!-- Contact Form Fields -->

                                        <div class="form-group">
                                            <label for="contact-phone">Phone Number:</label>
                                            <input type="tel" class="form-control" id="contact-phone" name="contact_phone" placeholder="Enter phone number">
                                        </div>

                                        <div class="form-group">
                                            <label for="contact-email">Email:</label>
                                            <input type="email" class="form-control" id="contact-email" name="contact_email" placeholder="Enter email">
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





                    $desc_title_1 = get_field('alinea_1_titel');
                    $desc_location_data = get_field('Locatiegegevens');

                    // echo '<pre>';
                    // print_r($desc_location_data);
                    // echo '</pre>';

                    if ($desc_title_1 && $desc_location_data) {
                        $locatiegegevens_items = $desc_location_data['locatiegegevens_items'];
                    
                        if (!empty($locatiegegevens_items)) {
                            echo '<ul class="list-unstyled mb-3">';
                            
                            foreach ($locatiegegevens_items as $index => $desc_location_item) {
                                echo '<li>';
                                

                                if ($desc_location_item['locatiegegevens_icon']) {
                                    echo $desc_location_item['locatiegegevens_icon'];
                                }

                                echo '  ';

                                // Display the name and an Edit button
                                if ($desc_location_item['locatiegegevens_name']) {
                                    echo '<span class="locatiegegevens_name mb-3" id="locatiegegevens_name_' . $index . '">' . esc_html($desc_location_item['locatiegegevens_name']) . '</span>';
                                }
                                
                                echo '</li>';
                            }
                            
                            echo '</ul>';
                        }
                    }
              
                    ?>

                </div>

                <div class="Contactgegevens-item">

                    <h3>Contactgegevens</h3>



                    <?php

                    $contact_location_data = get_field('Contactgegevens');
                    $contactgegevens_items = $contact_location_data['contactgegevens_items'];

                    echo '<ul class="list-unstyled mb-3" >';
                    foreach ($contactgegevens_items as $contact_location_item) {

                        // var_dump($contact_location_item);

                        echo '<li>';

                        // echo $desc_location_item['contactgegevens_image'];
                        // Display the image on the left
                        if ($contact_location_item['contactgegevens_icon']) {
                            echo $contact_location_item['contactgegevens_icon'];
                        }
                        echo ' ';
                        // Display the other field value on the right
                        if ($contact_location_item['contactgegevens_name']) {
                            echo '<span class="contactgegevens_name mb-3">' . esc_html($contact_location_item['contactgegevens_name']) . '</span>';
                        }

                        echo '</li>';
                    }
                    echo '</ul>';

                    ?>
                </div>

                <!-- You can add other post-related information here -->
                <a href="<?php the_permalink(); ?>" class="btn btn-primary">Bekijk locatie</a>
            </div>

            <div class="book-now-for-manager-section">
                <div class="p-3">
                    <a href="http://localhost:10038/booking/?location_id=<?php the_ID(); ?>" class="btn btn-success text-white h3">Book Now</a>
                </div>
            </div>
        </div>
    </div>


</div>



<?php
get_footer(); // Include your footer template
?>