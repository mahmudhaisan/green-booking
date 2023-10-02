<?php
get_header(); // Include your header template

?>

<div class="container mt-3 mb-5">
    <div class="row">
        <div class="col-md-12">
            <?php custom_breadcrumbs(); // Include your custom breadcrumb function
            ?>
        </div>
    </div>

    <div class="row image-gallery-grid align-items-center">
        <div class="col-md-12">
            <h1 class="d-inline"><?php the_title(); ?></h1>
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


            <div class="faciliteiten-item mt-5">
                <div class="faciliteiten-headerm mt-3 mb-3">
                    <div class="row align-items-center">
                        <div class="col-md-12">
                            <h3>Faciliteiten</h3>
                            <p class=""> Faciliteiten Selecteer hier wat uw locatie te bieden heeft.</p>
                        </div>

                    </div>
                </div>

                <div>


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
            <div class="right-side-location-contact bg-primary text-white p-3">




                <div class="Locatiegegevens-item">
                    <h3 class="d-inline">Locatiegegevens </h3>

                    <?php


                    $locations_data = get_field('locations_data');

                    ?>
                    <p class="location-name-icon mt-3"><i class="fas fa-location-pin"></i> <?php echo $locations_data['location_name_text']; ?></p>
                    <p class="location-name-icon"><i class="fas fa-globe"></i> <?php echo  $locations_data['location_website_url']; ?></p>
                    <p class="location-name-icon"><i class="fas fa-users"></i> <?php echo $locations_data['location_persons_number']; ?></p>
                    <p class="location-name-icon"><i class="fas fa-building"></i> <?php echo $locations_data['location_rooms_number']; ?></p>



                </div>

                <div class="Contactgegevens-item">
                    <h3 class="d-inline">Contactgegevens </h3>
                    <?php
                    $contacts_info = get_field('contacts_info');

                    ?>
                    <p class="location-name-icon mt-3"><i class="fas fa-phone"></i> <?php echo $contacts_info['location_phone']; ?></p>
                    <p class="location-name-icon"><i class="fas fa-envelope"></i> <?php echo $contacts_info['location_email']; ?></p>

                </div>

                <!-- You can add other post-related information here -->
                <a href="<?php the_permalink(); ?>" class="btn btn-primary">Bekijk locatie</a>
            </div>

        </div>
    </div>


</div>



<?php
get_footer(); // Include your footer template
?>