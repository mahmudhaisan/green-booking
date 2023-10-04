  <!-- Left Column: Search Filters -->
  <div class="col-md-4">
      <div class="card border border-1">
          <div class="card-body location-filter-top">
              <div class="radius-based-filter">
                  <h5 class="card-title">Zoek locaties</h5>
                  <!-- Add your search filter inputs and submit button here -->
                  <form method="POST" action="">
                      <div class="form-group">
                          <input type="text" class="form-control" id="location-input" name="filter1">
                          <input type="hidden" id="selected-lat" name="selected-lat">
                          <input type="hidden" id="selected-lng" name="selected-lng">
                      </div>

                      <div class="form-group">
                          <select class="form-control mt-3" id="radius-select" name="radius-select">
                              <option value="10">+10km</option>
                              <option value="15">+15km</option>
                              <option value="20">+20km</option>
                          </select>
                      </div>

                      <div class="form-group">
                          <button type="submit" class="btn btn-success text-white mt-3" id="filter-button" name="filter-button">Submit</button>
                      </div>
                  </form>

                  <div id="map"></div>

                  <div id="results-list"></div>

                  <!-- JavaScript for Google Maps Autocomplete and Map Display -->
                  <script>
                      function initMap() {
                          var map = new google.maps.Map(document.getElementById('map'), {
                              center: {
                                  lat: 23.8041,
                                  lng: 90.4152
                              },
                              zoom: 15
                          });

                          var autocomplete = new google.maps.places.Autocomplete(document.getElementById('location-input'), {
                              types: ['(regions)'], // Restrict to regions (countries)
                              componentRestrictions: {
                                  'country': 'NL'
                              } // Restrict to Netherlands (NL)
                          });

                          autocomplete.addListener('place_changed', function() {
                              var place = autocomplete.getPlace();
                              if (place.geometry) {
                                  var lat = place.geometry.location.lat(); // Get the full latitude
                                  var lng = place.geometry.location.lng(); // Get the full longitude
                                  document.getElementById('selected-lat').value = lat;
                                  document.getElementById('selected-lng').value = lng;
                              }
                          });
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