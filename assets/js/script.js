jQuery(document).ready(function ($) {



    // location Title Edit
    $('#location-title-edit').click(function (e) {
        e.preventDefault();
        console.log('yeah');
        $('#locationEditModal').modal('show');
    });


    $('#location-title-save-changes').click(function () {
        var newLocationTitle = $('#location-title-input').val();
        if (newLocationTitle.trim() !== '') {
            var ajaxUrl = greenbooking_plugin.ajaxurl;
            var locationId = $('#location-post-id').val();

            console.log('worl=king');
            // Perform an AJAX request to update the custom post title
            $.ajax({
                type: 'POST',
                url: ajaxUrl,
                data: {
                    action: 'update_custom_post_title',
                    newLocationTitle: newLocationTitle,
                    locationId: locationId,
                },
                success: function (response) {
                    console.log(response);
                    if (response) {
                        $('#locationEditModal').modal('hide');
                        location.reload();
                    } else {
                        // Handle errors or display a message
                        alert('Error: ' + response.message);
                    }
                }
            });
        } else {
            // Display an error message if the input is empty
            alert('Please enter a title.');
        }
    });



    // Location & Contatct Data Edit
    $('#location-data').click(function () {
        $('#locationContactEdit').modal('show');
    });

    $('#locationContactEdit form').submit(function (e) {
        e.preventDefault();
        // Validate all form fields
        var locationName = $('#location-name').val().trim();
        var locationWebsite = $('#location-website').val().trim();
        var locationPerson = $('#location-person').val().trim();
        var locationRooms = $('#location-rooms').val().trim();
        var contactPhone = $('#contact-phone').val().trim();
        var contactEmail = $('#contact-email').val().trim();

        var locationId = $('#location-post-id').val();

        // Check if any field is empty
        // if (
        //     locationName === '' ||
        //     locationWebsite === '' ||
        //     locationPerson === '' ||
        //     locationRooms === '' ||
        //     contactPhone === '' ||
        //     contactEmail === ''
        // ) {
        //     alert('Please fill in all fields.');
        //     return; // Prevent further processing
        // }


        var ajaxUrl = greenbooking_plugin.ajaxurl;
        // Serialize the form data
        var formData = $(this).serialize();


        // Send an Ajax request
        $.ajax({
            type: 'POST',
            url: ajaxUrl, // WordPress Ajax URL
            data: {
                action: 'update_location_contact_data', // Custom action to handle this request
                formData: formData, // Serialized form data
                locationId: locationId 
            },
            success: function (response) {
                // Handle the response here (e.g., show success message or update UI)
                // You can close the modal if needed
                $('#locationContactEdit').modal('hide');


                console.log(response);
            }
        });
    });


    // Close the modal when the close button is clicked
    $('.modal .close').click(function () {
        $('#locationEditModal').modal('hide');
    });




    // Close the modal when the close button is clicked
    $('.modal .close').click(function () {
        $('#locationContactEdit').modal('hide');
    });





    // Facilities Data edit
    $('#facilities-data-edit').click(function () {
        $('#facilitiesEditModal').modal('show');
    });


    $('.modal .close').click(function () {
        $('#facilitiesEditModal').modal('hide');
    });




});