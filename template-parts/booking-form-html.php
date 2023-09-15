<?php

// ob_start(); // Start output buffering to capture the HTML content

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
                <form method="" >
                    <div class="form-group mb-4">
                        <label for="locatie" class="text-success booking-field-label h4">Locatie</label>
                        <input type="text" class="form-control p-3" id="locatie" placeholder="Locatie" required>
                    </div>
                    <div class="form-group mb-4">
                        <label for="bedrijfsnaam"class="text-success booking-field-label h4">Bedrijfsnaam</label>
                        <input type="text" class="form-control p-3" id="bedrijfsnaam" placeholder="Bedrijfsnaam" required>
                    </div>
                    <div class="form-group mb-4" >
                        <label for="adres" class="text-success booking-field-label h4">Adres</label>
                        <input type="text" class="form-control p-3" id="adres" placeholder="Adres" required>
                    </div>
                    <div class="form-row row mb-4">
                        <div class="form-group col-md-6">
                            <label for="postcode" class="text-success booking-field-label h4">Postcode</label>
                            <input type="text" class="form-control p-3" id="postcode" placeholder="Postcode" required>
                        </div>
                        <div class="form-group col-md-6 ">
                            <label for="plaats" class="text-success booking-field-label h4">Plaats</label>
                            <input type="text" class="form-control p-3" id="plaats" placeholder="Plaats" required>
                        </div>
                    </div>
                    <div class="form-group mb-4">
                        <label for="contactperson" class="text-success booking-field-label h4">Contactpersoon</label>
                        <input type="text" class="form-control p-3" id="contactperson" placeholder="Contactpersoon" required>
                    </div>
                    <div class="form-group mb-4">
                        <label for="contactpersonEmail" class="text-success booking-field-label h4">Contactpersoon Email</label>
                        <input type="email" class="form-control p-3" id="contactpersonEmail" placeholder="Email" required>
                    </div>
                    <div class="form-group mb-4">
                        <label for="datumEventum" class="text-success booking-field-label h4">Datum Eventum</label>
                        <input type="date" class="form-control p-3" id="datumEventum" required>
                    </div>
                    <div class="form-group mb-4">
                        <label for="totalbedrag" class="text-success booking-field-label h4">Totalbedrag</label>
                        <input type="number" class="form-control p-3" id="totalbedrag" placeholder="Totalbedrag" required>
                    </div>
                    <div class="form-group mb-4">
                        <label class="text-success h4">Toepasbare Percentage</label><br>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="percentage" id="radio1" value="5%" required>
                            <label class="form-check-label" for="radio1">5%</label>
                        </div>
                        <div class="form-check form-check-inline">
                            <input class="form-check-input" type="radio" name="percentage" id="radio2" value="1%" required>
                            <label class="form-check-label" for="radio2">1%</label>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-success booking-submit-btn">Nu Doorgeven</button>
                </form>
            </div>
            <div class="col-md-4 ">
                <!-- Add content for the right column if needed -->
                <div class="right-side-location-contact bg-success text-white p-3">

                    <h3>Berekening </h3>
                    <h3>Berekening </h3>
                    <div class="booking-price-part">

                    <p class="booking-total-price">Totaalprijs <span>$</span> </p>
                    <p>0.5% <span>$</span> </p>
                    
                    <p class="booking-final-price">Totaal <span>$</span> </p>

                    </div>
                    <!-- You can add other post-related information here -->
                    <a href="" class="btn btn-primary">Je hebt ( ) bomen gespaard</a>
                </div>
            </div>
        </div>
    </div>

<?php

    // return ob_get_clean(); // Return the captured HTML content