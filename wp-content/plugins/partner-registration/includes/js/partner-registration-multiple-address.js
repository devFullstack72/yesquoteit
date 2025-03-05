function initMultipleAddressAutocomplete() {
    jQuery(document).on('focus', '.address-field', function () {
        var input = this;
        if (input.getAttribute('data-autocomplete-initialized')) return;
        input.setAttribute('data-autocomplete-initialized', 'true');

        var autocomplete = new google.maps.places.Autocomplete(input);
        google.maps.event.addDomListener(input, 'keydown', function(event) {
            if (event.key === 'Enter') {
                event.preventDefault();
            }
        });

        autocomplete.addListener('place_changed', function () {
            var place = autocomplete.getPlace();
            if (!place.geometry) return;

            var $container = jQuery(input).closest('.address-group');

            $container.find('.latitude').val(place.geometry.location.lat());
            $container.find('.longitude').val(place.geometry.location.lng());
            
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

                if (componentType in componentForm) {
                    addressData[componentType] = component[componentForm[componentType]];
                }
            });

            $container.find('.street_number').val(addressData.street_number);
            $container.find('.route').val(addressData.route);
            $container.find('.address2').val(addressData.address2);
            $container.find('.postal_code').val(addressData.postal_code);
            $container.find('.state').val(addressData.state);
            $container.find('.country').val(addressData.country);
        });
    });
}

// Function to add a new address field dynamically
function addAddressField() {
    let addressList = document.getElementById('address-list');
    let newRow = document.createElement('tr'); 
    newRow.classList.add('address-container');

    newRow.innerHTML = `
        <th><label>Address</label></th>
        <td class="address-group">
            <input type="text" name="addresses[]" class="regular-text address-field" placeholder="Enter address">
            <input type="text" name="latitude[]" class="latitude" placeholder="Latitude" readonly>
            <input type="text" name="longitude[]" class="longitude" placeholder="Longitude" readonly>
            <input type="text" name="street_number[]" class="street_number" placeholder="Street Number" readonly>
            <input type="text" name="route[]" class="route" placeholder="Route" readonly>
            <input type="text" name="address2[]" class="address2" placeholder="Address 2" readonly>
            <input type="text" name="postal_code[]" class="postal_code" placeholder="Postal Code" readonly>
            <input type="text" name="state[]" class="state" placeholder="State" readonly>
            <input type="text" name="country[]" class="country" placeholder="Country" readonly>

            <br><br><label>Service Area:</label>
            <select name="service_area[]" class="cls_slect-radius service-area">
                <option value="5">5 KM</option>
                <option value="10">10 KM</option>
                <option value="25">25 KM</option>
                <option value="50">50 KM</option>
                <option value="100">100 KM</option>
                <option value="250">250 KM</option>
                <option value="500">500 KM</option>
                <option value="entire">Entire Country</option>
                <option value="state">Entire State</option>
                <option value="other">Other Country</option>
                <option value="every">Everywhere</option>
                <option value="no_service">Not at this location</option>
            </select>

            <br><br><div class="other-country-container" style="display: none;">
                <label>Service provided in other Country:</label>
                <select name="other_country[]" class="cls_slect-radius">
                    <option value="">Select</option>
                    ${countriesOptions()}
                </select>
            </div>

            <div class="map-preview" style="height: 50px;"></div>
            <button type="button" class="button remove-address">Remove</button>
        </td>
    `;

    addressList.appendChild(newRow);

    initMultipleAddressAutocomplete();

    let newServiceArea = newRow.querySelector(".service-area");
    newServiceArea.addEventListener("change", function () {
        toggleOtherCountryField(this);
    });
}

// Event listener for Add Address button
document.getElementById('add-address').addEventListener('click', function () {
    addAddressField();
});

// Event delegation to remove an address field
document.getElementById('address-list').addEventListener('click', function (e) {
    if (e.target.classList.contains('remove-address')) {
        e.target.closest('tr').remove();
    }
});

// Initialize autocomplete on page load
document.addEventListener("DOMContentLoaded", function () {
    initMultipleAddressAutocomplete();
});

// Function to toggle Other Country field
function toggleOtherCountryField(selectElement) {
    let otherCountryContainer = selectElement.closest(".address-group").querySelector(".other-country-container");
    otherCountryContainer.style.display = selectElement.value === "other" ? "block" : "none";
}

// Function to generate country options dynamically
function countriesOptions() {
    if (typeof countryData === 'undefined' || !Array.isArray(countryData.countries)) {
        return '<option value="">No countries available</option>';
    }

    return countryData.countries
        .map(country => `<option value="${country.code}">${country.name}</option>`)
        .join('');
}

// Ensure existing fields handle service area change
document.addEventListener("DOMContentLoaded", function () {
    document.querySelectorAll(".service-area").forEach(function (select) {
        select.addEventListener("change", function () {
            toggleOtherCountryField(this);
        });
    });
});

// Disable button on form submission
jQuery(document).ready(function($) {
    $('.partner-registration-form, .partner-login-form').on('submit', function() {
        var $btn = $(this).find('button[type="submit"]');
        $btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Processing...');
    });
});

// Dropdown Toggle
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
