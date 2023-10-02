

<?php

$location_post = get_post($location_id);
$location_post_title =  $location_post->post_title; // Display the post title
$location_post_id =  $location_post->ID; // Display the post title


?>
<div class="container mt-5 mb-5">

    <div class="row">
        <div class="filter-heading">
            <h3><?php custom_breadcrumbs(); ?></h3>
            <h3>Aangesloten locaties</h3>
            <p>Heeft u een boeking? U kunt hem hier aan ons doorgeven zodat u meteen kunt zien hoeveel bomen er zijn gespaard. </p>
        </div>
    </div>
    <div class="row mt-3 booking-form-row">
        <div class="col-md-8">
            <form method="post" id="bookingForm">

                <div class="form-group mb-4">
                    <label for="locatie" class="text-success booking-field-label h4">Locatie</label>
                    <select class="form-control p-3" id="location-select-name">
                        <?php

                        // Query to retrieve your CPT locations

                        // Get the current user's ID
                        $current_user_id = get_current_user_id();

                        $args = array(
                            'post_type'      => 'locations',
                            'posts_per_page' => 1,
                            'order'          => 'DESC',
                            'meta_query'     => array(
                                array(
                                    'key'     => 'location_access_user_id',
                                    'value'   => $current_user_id,
                                    'compare' => '=',
                                ),
                            ),
                        );


                        $locations_query = new WP_Query($args);

                        if ($locations_query->have_posts()) :
                            while ($locations_query->have_posts()) :
                                $locations_query->the_post();
                                $location_id = get_the_ID();
                                $location_title = get_the_title();
                        ?>
                                <option value="<?php echo esc_attr($location_title); ?>" <?php selected($location_id, $location_post_id); ?>><?php echo esc_html($location_title); ?></option>
                        <?php
                            endwhile;
                            wp_reset_postdata();
                        else :
                            echo '<option value="">No locations found</option>';
                        endif;
                        ?>
                    </select>
                </div>
















                <div class="form-group mb-4">
                    <label for="bedrijfsnaam" class="text-success booking-field-label h4">Bedrijfsnaam</label>
                    <input type="text" class="form-control p-3" id="bedrijfsnaam" placeholder="Bedrijfsnaam">
                </div>
                <div class="form-group mb-4">
                    <label for="adres" class="text-success booking-field-label h4">Adres</label>
                    <input type="text" class="form-control p-3" id="adres" placeholder="Adres">
                </div>
                <div class="form-row row mb-4">
                    <div class="form-group col-md-6">
                        <label for="postcode" class="text-success booking-field-label h4">Postcode</label>
                        <input type="text" class="form-control p-3" id="postcode" placeholder="Postcode">
                    </div>
                    <div class="form-group col-md-6 ">
                        <label for="plaats" class="text-success booking-field-label h4">Plaats</label>
                        <input type="text" class="form-control p-3" id="plaats" placeholder="Plaats">
                    </div>
                </div>
                <div class="form-group mb-4">
                    <label for="contactperson" class="text-success booking-field-label h4">Contactpersoon</label>
                    <input type="text" class="form-control p-3" id="contactperson" placeholder="Contactpersoon">
                </div>
                <div class="form-group mb-4">
                    <label for="contactpersonEmail" class="text-success booking-field-label h4">Contactpersoon Email</label>
                    <input type="email" class="form-control p-3" id="contactpersonEmail" placeholder="Email">
                </div>
                <div class="form-group mb-4">
                    <label for="datumEventum" class="text-success booking-field-label h4">Datum Eventum</label>
                    <input type="date" class="form-control p-3" id="datumEventum">
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label for="booking-amount-input" class="text-success booking-field-label h4">Totalbedrag (€)</label>
                            <input type="number" class="form-control  p-3" id="booking-amount-input" placeholder="€">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-4">
                            <label class="text-success h4">Toepasbare Percentage</label><br>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input booking-form-price-input radio-option" type="radio" name="percentage" data-value="0.5" id="radio1" value="5%" checked>
                                <label class="form-check-label" for="radio1">0.5%</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input booking-form-price-input radio-option" type="radio" name="percentage" id="radio2" data-value="1" value="1%">
                                <label class="form-check-label" for="radio2">1%</label>
                            </div>
                        </div>


                    </div>
                </div>


                <p>Je hebt (<span class="total-tree-plant" id="planted-tree-count">0</span>) bomen gespaard</p>
                <button type="submit" class="btn btn-success booking-submit-btn">Nu Doorgeven</button>
            </form>
        </div>
        <div class="col-md-4 ">
            <!-- Add content for the right column if needed -->
            <div class="right-side-location-contact bg-success text-white p-3">
                <h3>Berekening </h3>
                <div class="booking-price-part">

                    <p class="booking-total-price">Totaalprijs
                        <span id="total-user-amount">€</span>
                    </p>
                    <p>0.5%

                        <span id="percentage-amount-total">€</span>

                    </p>

                    <p class="">Totaal <span class="">€</span id="booking-final-price"> </p>

                </div>
                <!-- You can add other post-related information here -->
                <a href="" class="btn btn-primary">Je hebt (<span class="total-tree-plant" id="planted-tree-count">0</span>) bomen gespaard</a>
            </div>
        </div>
    </div>
</div>

