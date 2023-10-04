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
        if (
            locationName === '' ||
            locationWebsite === '' ||
            locationPerson === '' ||
            locationRooms === ''
        ) {
            alert('Please fill in all fields.');
            return; // Prevent further processing
        }


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
                location.reload();
                // console.log(response);
            }
        });
    });


    // Close the modal when the close button is clicked
    $('.modal .close').click(function () {
        $('#locationEditModal').modal('hide');
    });


    // Location & Contatct Data Edit
    $('#contact-data').click(function () {
        $('#contactEdit').modal('show');
    });

    $('#contactEdit form').submit(function (e) {
        e.preventDefault();
        // Validate all form fields
        var contactPhone = $('#contact-phone').val().trim();
        var contactEmail = $('#contact-email').val().trim();

        var locationId = $('#location-post-id').val();

        // Check if any field is empty
        if (
            contactPhone === '' ||
            contactEmail === ''
        ) {
            alert('Please fill in all fields.');
            return; // Prevent further processing
        }

        var ajaxUrl = greenbooking_plugin.ajaxurl;
        // Serialize the form data
        var formData = $(this).serialize();


        // Send an Ajax request
        $.ajax({
            type: 'POST',
            url: ajaxUrl, // WordPress Ajax URL
            data: {
                action: 'update_contact_data', // Custom action to handle this request
                formData: formData, // Serialized form data
                locationId: locationId
            },
            success: function (response) {
                // Handle the response here (e.g., show success message or update UI)
                // You can close the modal if needed
                $('#contactEdit').modal('hide');

                location.reload();

                // console.log(response);
            }
        });
    });


    // Close the modal when the close button is clicked
    $('.modal .close').click(function () {
        $('#locationEditModal').modal('hide');
    });

    // Close the modal when the close button is clicked
    $('.modal .close').click(function () {
        $('#contactEdit').modal('hide');
    });





    // Facilities Data edit
    $('#facilities-data-edit').click(function () {
        $('#facilitiesEditModal').modal('show');
    });


    $('.modal .close').click(function () {
        $('#facilitiesEditModal').modal('hide');
    });


    $('#saveFacilities').click(function () {

        // alert('working');
        var selectedValues = [];


        var locationId = $('#location-post-id').val();
        var ajaxUrl = greenbooking_plugin.ajaxurl;
        // Loop through all checkboxes and add selected values to the array
        // Loop through all checkboxes and add selected key-value pairs to the object
        $('input[type="checkbox"]:checked').each(function () {
            var key = $(this).val(); // Assuming the checkbox value is the key
            var value = $(this).val(); // Assuming data-id contains the value



            var keyValueObject = value;

            selectedValues.push(keyValueObject); // Store the key-value pair in the array
        });


        // console.log(selectedValues);

        // Send an AJAX request to update the ACF field
        $.ajax({
            url: ajaxUrl, // Use the URL provided by WordPress
            type: 'POST',
            data: {
                action: 'update_facilities_field_options',
                selected_values: selectedValues,
                locationId: locationId
            },
            success: function (response) {

                $('#facilitiesEditModal').modal('hide');

                location.reload();
            },
            error: function (error) {
                // Handle errors (if any)
                console.error(error);
            },
        });
    });





    $('#change-featured-image').click(function () {
        var ajaxUrl = greenbooking_plugin.ajaxurl;
        var locationId = $('#location-post-id').val();

        // Create an input element for file upload
        var fileInput = $('<input type="file" accept="image/*">');
        fileInput.on('change', function () {
            var file = this.files[0];

            if (file) {
                // Use the FormData object to send the file via AJAX
                var formData = new FormData();
                formData.append('action', 'update_featured_image');
                formData.append('post_id', locationId);
                formData.append('image', file);

                // AJAX request to update the featured image
                $.ajax({
                    type: 'POST',
                    url: ajaxUrl,
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function (response) {
                        if (response.success) {
                            // Reload the page or update the featured image container if needed
                            location.reload();
                        } else {
                            alert('Error updating featured image.');
                        }
                    }
                });
            }
        });

        // Trigger the file input dialog
        fileInput.click();
    });


    $('#change-gallery').click(function () {
        var ajaxUrl = greenbooking_plugin.ajaxurl;
        var currentImages = $('#gallery-container img').length;
        var maxImages = 4 - currentImages; // Calculate the remaining images allowed

        if (maxImages <= 0) {
            alert('You can select up to four images.');
            return;
        }

        var frame = wp.media({
            title: 'Select or Upload Gallery Images',
            button: {
                text: 'Use these images'
            },
            multiple: true, // Allow multiple image selection
            library: {
                type: 'image'
            }
        });

        frame.on('select', function () {
            var attachments = frame.state().get('selection').toJSON();

            if (attachments.length > maxImages) {
                alert('You can select up to four images.');
                return;
            }

            var galleryImages = [];

            if (attachments.length > 0) {
                attachments.forEach(function (attachment) {
                    galleryImages.push(attachment.id);
                });
            }

            // Send the updated galleryImages to the server via AJAX
            $.ajax({
                type: 'POST',
                url: ajaxUrl,
                data: {
                    action: 'update_gallery_images', // AJAX action name
                    gallery_images: galleryImages,
                    post_id: 539,
                },
                success: function (response) {
                    if (response.success) {

                        // Update the ACF gallery field
                        alert('Gallery images updated successfully.'); // You can replace this with a custom success message
                        location.reload();
                    } else {
                        alert('Error updating gallery images.');
                    }
                }
            });
        });

        frame.open();
    });



    $('#bookingForm').submit(function (e) {
        e.preventDefault(); // Prevent the default form submission behavior

        var ajaxUrl = greenbooking_plugin.ajaxurl;

        // Reset any previous validation errors
        $('.form-control').removeClass('is-invalid');
        $('.invalid-feedback').remove();

        var isValid = true;

        // Use .each() to iterate through form fields and validate each one
        $('.form-control').each(function () {
            var $field = $(this);
            if ($field.val() === '') {
                $field.addClass('is-invalid');
                $field.after('<div class="invalid-feedback">This field is required.</div>');
                isValid = false;
            }
        });

        if (isValid) {
            var locationName = $('#location-select-name').val();
            var createdFor = $('#contactperson').val();
            var locationUserTotal = $('#total-user-amount').text();
            var totalTrees = $('#planted-tree-count').text();
            var selectedDate = $("#datumEventum").val();



            totalTrees = parseInt(totalTrees);

            // Check if totalTrees is greater than 0
            if (totalTrees > 0) {
                // All required fields are filled out correctly and totalTrees > 0


                // Perform your AJAX processing here
                $.ajax({
                    url: ajaxUrl, // Replace with your AJAX endpoint URL
                    method: 'POST',
                    action: 'booking_form_submit', // AJAX action name
                    data: {
                        action: 'booking_form_submit', // WordPress action hook
                        serializeFormData: $('#bookingForm').serialize(),
                        locationName: locationName,
                        locationUserTotal: locationUserTotal,
                        totalTrees: totalTrees,
                        selectedDate: selectedDate,
                        createdFor: createdFor,

                    },
                    success: function (response) {
                        var currentHost = window.location.host;

                        // alert(currentHost);
                        var newUrl = 'http://' + currentHost + '/booking/?success=true';
                        // Redirect to the new page
                        window.location.href = newUrl;


                        // Handle the AJAX response, e.g., display a success message or update the page content
                    },
                    error: function (xhr, status, error) {
                        // Handle AJAX errors, if any
                    }
                });
            } else {
                // Show an error message because totalTrees <= 0
                alert('Total trees must be greater than 0 to submit the form.');
            }
        } else {
            // Show an error message because the form is not valid
            alert('Not Working');
        }
    });





    // JavaScript to handle radio button selection
    $('.booking-form-price-input').change(function () {

        $('.booking-form-price-input').prop('checked', false); // Uncheck all radio buttons with class 'radio-option'
        $(this).prop('checked', true); // Check the clicked radio button

        var userInputAmount = parseFloat($('#booking-amount-input').val());

        if (isNaN(userInputAmount)) {
            userInputAmount = 0;
        }

        var userSelectedPercentage = parseFloat($(this).data('value'));



        var userPercentageAmount = userInputAmount * (userSelectedPercentage / 100);
        var numberOfPlants = Math.floor(userPercentageAmount / 5);

        $('.total-tree-plant').empty().append(numberOfPlants);
        $('#total-user-amount').empty().append(userInputAmount);
        $('#percentage-amount-total').empty().append(userPercentageAmount);


    });


    $('#booking-amount-input').on('input', function () {

        var userInputAmount = parseFloat($(this).val());
        if (isNaN(userInputAmount)) {
            userInputAmount = 0;
        }
        var userSelectedPercentage = parseFloat($('input[name="percentage"]:checked').data('value'));
        var userPercentageAmount = userInputAmount * (userSelectedPercentage / 100);

        var numberOfPlants = Math.floor(userPercentageAmount / 5);

        $('.total-tree-plant').empty().append(numberOfPlants);

        $('#total-user-amount').empty().append(userInputAmount);
        $('#percentage-amount-total').empty().append(userPercentageAmount);


    });




    $('#location-person-filter, #minimum-person-filter, .location-facility-checkbox').on('input change', function () {
        $('#loading-spinner').show();
        var ajaxUrl = greenbooking_plugin.ajaxurl;
        var personQuantityValue = $('#location-person-filter').val();
        var minimumPersonQuantityValue = $('#minimum-person-filter').val();

        // Get selected checkboxes for facilities
        var selectedFacilities = [];
        $('.location-facility-checkbox:checked').each(function () {
            selectedFacilities.push($(this).val());
        });

        $.ajax({
            type: 'post',
            url: ajaxUrl,
            data: {
                action: 'combined_location_filter',
                personQuantityValue: personQuantityValue,
                minimumPersonQuantityValue: minimumPersonQuantityValue,
                facilities: selectedFacilities,
            },
            success: function (response) {
                $('#loading-spinner').hide();
                // Update the content with the AJAX response
                $('.archieve-right-items .row').html(response);
            },
            complete: function () {
                $('#loading-spinner').hide();
            }
        });
    });



    function yearBasedChartData(){

    
        var ajaxUrl = greenbooking_plugin.ajaxurl;
        const yearDropdown = $('#yearDropdown');
        const lineChartCanvas = $('#lineChart')[0].getContext('2d');
        const currentYear = new Date().getFullYear(); // Get the current year
        const currentMonth = new Date().getMonth(); // Get the current month (0-based index)
    
        // Initialize the chart with an empty dataset
        const lineChart = new Chart(lineChartCanvas, {
            type: 'line',
            data: {
                labels: [], // Months will go here
                datasets: [],
            },
            options: {
                scales: {
                    x: {
                        grid: {
                            drawOnChartArea: false, // Hide the x-axis grid lines
                        },
                    },
                    y: {
                        grid: {
                            drawOnChartArea: false, // Hide the y-axis grid lines
                        },
                        suggestedMin: 0, // Ensure the y-axis starts from 0
                    },
                },
            },
        });
    
        // Function to update chart data based on the selected year
        function updateChartData(selectedYear) {
            // Clear existing datasets
            lineChart.data.datasets = [];
    
            // Use AJAX to fetch the yearData from your PHP function
            $.ajax({
                type: 'POST',
                url: ajaxUrl, // Replace with the URL to your PHP function
                data: {
                    action: 'get_year_data', // Replace with the action name used in your PHP function
                    selectedYear: selectedYear,
                },
                success: function (response) {
                  
                    if (response && response.yearData) {

                        
                        // Use the fetched yearData to update the chart
                        const yearDataForSelectedYear = response.yearData[selectedYear];
    
        
                        // Extract months and counts from the yearDataForSelectedYear
                        const months = Object.keys(yearDataForSelectedYear);
                        const countsData = Object.values(yearDataForSelectedYear);
    
                        // Create a dataset with the custom data
                        const dataset = {
                            label: selectedYear.toString(),
                            data: countsData,
                            borderColor: '#0c474f',
                            fill: false,
                            pointRadius: 5,
                        };
    
                        // Add the dataset to the chart
                        lineChart.data.labels = months;
                        lineChart.data.datasets.push(dataset);
    
                        // Update the chart
                        lineChart.update();
                    }
                },
            });
        }
    
        // Populate the year dropdown with years since 2020
        for (let year = 2022; year <= currentYear; year++) {
            yearDropdown.append($('<option>', {
                value: year,
                text: year,
            }));
        }
    
        // Set the default selected year to the current year
        yearDropdown.val(currentYear);
    
        // Event listener to update chart when the year dropdown changes
        yearDropdown.on('change', function () {
            const selectedYear = yearDropdown.val();
            updateChartData(selectedYear);
        });
    
        // Initial chart setup for the current year and month
        updateChartData(currentYear);
    };
    

    yearBasedChartData();

});

