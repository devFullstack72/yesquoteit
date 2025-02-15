function initialize() {
    var input = document.getElementById('autocomplete_shortcode');

    if (!input) {
        return;
    }

    var default_address = input.value || ''; // Avoid errors if empty

    // var default_address = input.value;
    var autocomplete = new google.maps.places.Autocomplete(input);

    // Ensure the suggestions appear properly in the modal
    google.maps.event.addDomListener(input, 'keydown', function(event) {
        if (event.key === 'Enter') {
            event.preventDefault(); // Prevent form submission on Enter key
        }
    });

    // Move the suggestion box to the body
    setTimeout(function() {
        $(".pac-container").appendTo("body");
    }, 500);
    
    var map = new google.maps.Map(document.getElementById('map'), {
        zoom: 15,
        center: { lat: 40.7128, lng: -74.0060 } // Default to New York
    });

    var marker = new google.maps.Marker({
        map: map
    });

    autocomplete.addListener('place_changed', function () {
        var place = autocomplete.getPlace();
        if (!place.geometry) {
            return;
        }

        var addressComponents = place.address_components;
        
        setAddressData(addressComponents);

        // Update map position
        map.setCenter(place.geometry.location);
        marker.setPosition(place.geometry.location);
    });

    if (default_address) {
        var geocoder = new google.maps.Geocoder();
        geocoder.geocode({ 'address': default_address }, function(results, status) {
            if (status === 'OK' && results[0]) {
                var place = results[0];
                input.value = place.formatted_address;
                autocomplete.set('place', place);

                map.setCenter(place.geometry.location);
                marker.setPosition(place.geometry.location);

                var addressComponents = place.address_components;

                setAddressData(addressComponents);
            }
        });
    }
}

// Ensure Google Maps API is fully loaded before initializing
document.addEventListener("DOMContentLoaded", function () {
    if (typeof google !== "undefined" && typeof google.maps !== "undefined") {
        initialize();
    } else {
        console.error("Google Maps API not loaded.");
    }
});

function setAddressData(addressComponents) {
    var componentForm = {
        street_number: 'short_name',
        route: 'long_name',
        locality: 'long_name',
        administrative_area_level_1: 'short_name',
        postal_code: 'short_name',
        country: 'long_name'
    };

    for (var i = 0; i < addressComponents.length; i++) {
        var addressType = addressComponents[i].types[0];
        if (componentForm[addressType]) {
            var val = addressComponents[i][componentForm[addressType]];
            var inputField = document.getElementById(addressType);
            if (inputField) {
                inputField.value = val;
            }
        }
    }
}


$(document).on('focus', '#autocomplete_shortcode', function () {
    setTimeout(function () {
        $('.pac-container').css({
            top: $("#autocomplete_shortcode").offset().top + $("#autocomplete_shortcode").outerHeight(),
            left: $("#autocomplete_shortcode").offset().left,
            width: $("#autocomplete_shortcode").outerWidth(),
            position: "absolute"
        });
    }, 500);
});