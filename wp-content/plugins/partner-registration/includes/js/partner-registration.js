function initAutocomplete() {
    var input = document.getElementById('autocomplete');
    if (!input) return; // Ensure the input field exists

    var autocomplete = new google.maps.places.Autocomplete(input);

    // Ensure the suggestions appear properly in the modal
    google.maps.event.addDomListener(input, 'keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Prevent form submission on Enter key
        }
    });

    var default_latitude = jQuery('.partner-registration-form #latitude').val();
    if (default_latitude.toString().length == 0) {
        default_latitude = 40.7128;
    }

    var default_longitude = jQuery('.partner-registration-form #longitude').val();
    if (default_longitude.toString().length == 0) {
        default_longitude = 40.7128;
    }

    var map = new google.maps.Map(document.getElementById('map-preview'), {
        zoom: 15,
        center: { lat: parseFloat(default_latitude), lng: parseFloat(default_longitude) } // Default to New York
    });

    var marker = new google.maps.Marker({
        map: map
    });

    autocomplete.addListener('place_changed', function () {
        var place = autocomplete.getPlace();
        if (!place.geometry) return;

        document.getElementById('latitude').value = place.geometry.location.lat();
        document.getElementById('longitude').value = place.geometry.location.lng();

        var addressComponents = place.address_components;
        var componentForm = {
            street_number: 'short_name',
            route: 'long_name',
            address2: 'long_name',
            postal_code: 'short_name',
            state: 'short_name',
            country: 'long_name'
        };

        var addressData = {
            street_number: '',
            route: '',
            address2: '',
            postal_code: '',
            state: '',
            country: ''
        };

        addressComponents.forEach(function (component) {
            var componentType = component.types[0];

            if (componentType === 'street_number') {
                addressData.street_number = component[componentForm.street_number];
            }
            if (componentType === 'route') {
                addressData.route = component[componentForm.route];
            }
            if (componentType === 'sublocality' || componentType === 'neighborhood') {
                addressData.address2 = component[componentForm.address2];
            }
            if (componentType === 'postal_code') {
                addressData.postal_code = component[componentForm.postal_code];
            }
            if (componentType === 'administrative_area_level_1') {
                addressData.state = component[componentForm.state];
            }
            if (componentType === 'country') {
                addressData.country = component[componentForm.country];
            }
        });

        document.getElementById('street_number').value = addressData.street_number;
        document.getElementById('route').value = addressData.route;
        document.getElementById('address2').value = addressData.address2;
        document.getElementById('postal_code').value = addressData.postal_code;
        document.getElementById('state').value = addressData.state;
        document.getElementById('country').value = addressData.country;

        // var mapImageUrl = `https://maps.googleapis.com/maps/api/staticmap?center=${place.geometry.location.lat()},${place.geometry.location.lng()}&zoom=15&size=800x300&maptype=roadmap&markers=color:red%7Clabel:S%7C${place.geometry.location.lat()},${place.geometry.location.lng()}&key=AIzaSyADTn5LfNUzzbgxNd-TFiNbVwAf0JNoNBw`;

        // document.getElementById('map-image').src = mapImageUrl;
        // document.getElementById('map-image').style.display = 'block';

        // Update map position
        map.setCenter(place.geometry.location);
        marker.setPosition(place.geometry.location);
    });
}

document.addEventListener("DOMContentLoaded", function () {
    initAutocomplete();
});

function on_country()
{
    var country = $('#country').val();
    $('#other_country option[value="' + country + '"]').remove();
}

jQuery(document).ready(function ($) {

var radius = $('#radius').val();
if (radius == 'other') {
    $("#show_country").show();
}

$('#radius').on('change', function() {
    if ( this.value == 'other')
    {
    $("#show_country").show();
    }
    else
    {
    $("#show_country").hide();
    }
});

$('#radius').on('change', function() {
	if($('#radius').val()=='no_service')
	{ 
        alert('No problems, after registering you will be able to add as many service locations in other areas as required');
	}
});
});


jQuery(document).ready(function($) {
    $('.partner-registration-form, .partner-login-form').on('submit', function() {
        var $btn = $(this).find('button[type="submit"]');
        $btn.prop('disabled', true); // Disable button
        $btn.html('<i class="fa fa-spinner fa-spin"></i> Processing...'); // Change button text
    });
});

document.addEventListener('DOMContentLoaded', function () {
    let dropdownToggle = document.getElementById('partnerDropdown');
    let dropdown = dropdownToggle.parentElement;

    dropdownToggle.addEventListener('click', function (event) {
        event.stopPropagation();
        dropdown.classList.toggle('active');
    });

    document.addEventListener('click', function () {
        dropdown.classList.remove('active');
    });
});