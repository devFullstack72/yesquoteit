function initAutocomplete() {
    var input = document.getElementById('autocomplete');
    if (!input) return; // Ensure the input field exists

    var autocomplete = new google.maps.places.Autocomplete(input, {
        types: ['geocode'],
        componentRestrictions: { country: "us" }
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

        var mapImageUrl = `https://maps.googleapis.com/maps/api/staticmap?center=${place.geometry.location.lat()},${place.geometry.location.lng()}&zoom=15&size=400x300&maptype=roadmap&markers=color:red%7Clabel:S%7C${place.geometry.location.lat()},${place.geometry.location.lng()}&key=AIzaSyDuoh4RV3jwuAD72LBq02e3rx4-iZa-wLc`;

        document.getElementById('map-image').src = mapImageUrl;
        document.getElementById('map-image').style.display = 'block';
    });
}

document.addEventListener("DOMContentLoaded", function () {
    initAutocomplete();
});
