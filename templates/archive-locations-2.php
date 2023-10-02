<?php
get_header();
?>

<div class="container mt-4 locations-archive-body">
    <div class="filter-heading">
        <h3><?php custom_breadcrumbs(); ?></h3>
        <h3>Aangesloten locaties</h3>
    </div>
    <div class="row mb-5">
        <!-- Left Column: Search Filters -->
        <div class="col-md-4 ">
            <div class="card border border-1">
                <div class="card-body location-filter-top">


                    <div class="radius-based-filter">


                        <h5 class="card-title">Zoek locaties</h5>
                        <!-- Add your search filter inputs and submit button here -->
                        <form>
                            <div class="form-group">
                                <div class="form-group">
                                    <input type="text" class="form-control" id="location-input" name="filter1">
                                    <input type="hidden" id="selected-lat" name="selected-lat">
                                    <input type="hidden" id="selected-lng" name="selected-lng">
                                </div>

                                <div class="form-group">
                                    <select class="form-control mt-3" id="radius-select">
                                        <option value="10">+10km</option>
                                        <option value="15">+15km</option>
                                    </select>
                                </div>

                                <div class="form-group">
                                    <button type="submit" class="btn  btn-success text-white mt-3 " id="filter-button">Submit</button>
                                </div>

                            </div>
                        </form>


                        <div id="map" style="height: 400px; display: none"></div>

                        <div id="results-list">

                        </div>







                        <script>
                            function initMap() {
                                // Initialize the map
                                var map = new google.maps.Map(document.getElementById('map'), {
                                    center: {
                                        lat: 23.8041,
                                        lng: 90.4152
                                    },
                                    zoom: 15 // Adjust the zoom level as needed
                                });

                                // Initialize the PlacesService
                                var service = new google.maps.places.PlacesService(map);

                                // Create an autocomplete search box
                                var input = document.getElementById('location-input');
                                var autocomplete = new google.maps.places.Autocomplete(input, {
                                    types: ['(regions)'], // Restrict to regions (countries)
                                    componentRestrictions: {
                                        'country': 'NL'
                                    } // Restrict to Netherlands (NL)
                                });
                                // Define the search when the "Search" button is clicked
                                document.getElementById('filter-button').addEventListener('click', function() {
                                    performSearch();
                                });

                                // Define the nearby search function
                                function performSearch() {
                                    var place = autocomplete.getPlace();

                                    if (place.geometry && place.geometry.location) {
                                        var location = place.geometry.location;

                                        // Store the latitude and longitude in hidden input fields
                                        document.getElementById('selected-lat').value = location.lat();
                                        document.getElementById('selected-lng').value = location.lng();


                                        // Define the nearby search request
                                        var request = {
                                            location: location,
                                            radius: 20000, // Specify the radius in meters
                                            // type: 'business' // Specify the type of place you want to search for
                                        };

                                        // // Perform the nearby search
                                        // service.nearbySearch(request, function(results, status) {
                                        //     if (status === google.maps.places.PlacesServiceStatus.OK) {
                                        //         displayResults(results);
                                        //     }
                                        // });

                                        service.nearbySearch(request, handleResults);

                                    }
                                }


                                function handleResults(results, status, pagination) {

                                    if (status === google.maps.places.PlacesServiceStatus.OK) {
                                        console.log(results);
                                        displayResults(results);
                                        if (pagination.hasNextPage) {
                                            // Fetch the next page of results
                                            pagination.nextPage();
                                        }
                                    } else if (status === google.maps.places.PlacesServiceStatus.ZERO_RESULTS) {
                                        // No more results to fetch
                                    } else {
                                        // Handle errors
                                    }
                                }


                                function displayResults(results) {
                                    var resultsList = document.getElementById('results-list');
                                    resultsList.innerHTML = ''; // Clear previous results

                                    for (var i = 0; i < results.length; i++) {
                                        var place = results[i];
                                        var listItem = document.createElement('li');
                                        listItem.textContent = place.name;
                                        resultsList.appendChild(listItem);
                                    }
                                }


                            }
                        </script>


                    </div>
                </div>

                <div class="card-body ">

                    <div class="form-group mt-3">
                        <label for="location-name" class="h5">Minimaal Aantal personen</label>
                        <input type="number" class="form-control" id="location-person-filter" name="minimum_person_filter">
                    </div>

                    <div class="form-group mt-3">
                        <label for="location-name" class="h5">Aantal zalen</label>
                        <input type="text" class="form-control" id="minimum-person-filter" name="minimum_person_filter">
                    </div>

                    <div class="form-group mt-3">
                        <label for="location-name" class="h5">Faciliteiten</label>
                        <?php
                        $field_name = 'location-facilities'; // Replace with the name or key of your ACF field

                        $field = get_field_object($field_name);

                        if ($field && isset($field['choices']) && is_array($field['choices'])) {
                            $checkbox_options = $field['choices'];

                            // Loop through the available checkbox options and create checkboxes
                            foreach ($checkbox_options as $value => $label) {
                                echo '<div class="form-check">';
                                echo '<input class="form-check-input location-facility-checkbox" type="checkbox" name="' . $field_name . '[]" value="' . esc_attr($value) . '" id="' . $field_name . '-' . esc_attr($value) . '">';
                                echo '<label class="form-check-label" for="' . $field_name . '-' . esc_attr($value) . '">' . esc_html($label) . '</label>';
                                echo '</div>';
                            }
                        } else {
                            echo 'No checkbox options found for the field.';
                        }
                        ?>
                    </div>

                </div>

            </div>
        </div>



        <!-- Right Column: Post List -->

        <div class="col-md-8 archieve-right-items">
            <div class="spinner-container" id="loading-spinner" style="display: none;">
                <div class="spinner"></div>
                bezig met laden
            </div>






            <div class="row">



                <?php
                // The WordPress Loop
                if (have_posts()) :
                    while (have_posts()) :
                        the_post();
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
                else :
                    echo 'No posts found.';
                endif;
                ?>
            </div>





            
        </div>
    </div>
</div>

<?php
get_footer();
?>