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

    <div class="row image-gallery-grid">
        <div class="col-md-12">
            <h1><?php the_title(); ?></h1>
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
                <div class="location-title">
                    <h3 class="location-text">Locatie omschrijving</h3>
                    <h4 class="location-title-label"><?php echo $title1_label; ?></h4>
                    <p class="location-title-val"><?php echo $title1_value; ?></p>
                </div>
                <div class="location-desc">
                    <h4 class="location-desc-label"><?php echo $desc1_label; ?></h4>
                    <p class="location-desc-val"><?php echo $desc1_value; ?></p>
                </div>
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
                           <a href="" class="btn btn-info">Click for edit</a>
                        </div>
                    </div>


                </div>

                <?php

                $faciliteiten_data = get_field('faciliteiten');
                $faciliteiten_items =  $faciliteiten_data['faciliteiten_items'];


                if (!empty($faciliteiten_items)) {
                    echo '<div class="row">';
                    $count = 0;

                    foreach ($faciliteiten_items as $faciliteiten_item) {
                        if ($count % 2 == 0) {
                            // Start a new row
                            echo '</div><div class="row">';
                        }

                        echo '<div class="col-md-6">';
                        echo '<ul class="list-unstyled mb-3">';

                        echo '<li>';

                        // Display the image on the left
                        if ($faciliteiten_item['faciliteiten_image']) {
                            echo '<img src="' . $faciliteiten_item['faciliteiten_image'] . '" alt="Location Image" class="location-image mr-3">';
                        }

                        // Display the other field value on the right
                        if ($faciliteiten_item['faciliteiten_name']) {
                            echo '<span class="faciliteiten_name mb-3">' . esc_html($faciliteiten_item['faciliteiten_name']) . '</span>';
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

                <h3>Locatiegegevens <span class="text-info"> edit</span></h3>

                <div class="Locatiegegevens-item">


                    <?php
                    $desc_title_1 = get_field('alinea_1_titel');
                    $desc_location_data = get_field('Locatiegegevens');

                    if ($desc_title_1 && $desc_location_data) {
                        $locatiegegevens_items = $desc_location_data['locatiegegevens_items'];
                        if (!empty($locatiegegevens_items)) {
                            echo '<ul class="list-unstyled mb-3" >';
                            foreach ($locatiegegevens_items as $desc_location_item) {
                                echo '<li>';

                                // echo $desc_location_item['locatiegegevens_image'];
                                // Display the image on the left
                                if ($desc_location_item['locatiegegevens_image']) {
                                    echo '<img src="' . $desc_location_item['locatiegegevens_image'] . '" alt="Location Image" class="location-image mr-3">';
                                }

                                // Display the other field value on the right
                                if ($desc_location_item['locatiegegevens_name']) {
                                    echo '<span class="locatiegegevens_name mb-3">' . esc_html($desc_location_item['locatiegegevens_name']) . '</span>';
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
                        if ($contact_location_item['contactgegevens_image']) {
                            echo '<img src="' . $contact_location_item['contactgegevens_image'] . '" alt="Location Image" class="location-image mr-3">';
                        }

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
                    <a href="http://localhost:10038/booking/?id=<?php the_ID(); ?>"  class="btn btn-success text-white h3">Book Now</a>
                </div>
            </div>
        </div>
    </div>


</div>



<?php
get_footer(); // Include your footer template
?>