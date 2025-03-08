jQuery(document).ready(function ($) {

    var quote_id_customer_selected_for_close = '';

    $('.close-quote-by-customer').on('click', function() {
        quote_id_customer_selected_for_close = $(this).data('id');
        $('#quote-close-modal').modal('show');

        loadQuotePartners(quote_id_customer_selected_for_close);
    });

    function loadQuotePartners(quote_id_customer_selected_for_close) {
        $.ajax({
            type: "POST",
            url: ajax_object.ajax_url,
            data: {
                action: "get_quote_partners_by_quote_id",
                id: quote_id_customer_selected_for_close,
                security: ajax_object.nonce // Use nonce
            },
            beforeSend: function () {
                // Show loading spinner before fetching data
                $("#partner-list").html(`
                    <tr id="loading-spinner">
                        <td colspan="2" class="text-center">
                            <i class="fa fa-spinner fa-spin fa-2x"></i> Loading...
                        </td>
                    </tr>
                `);
            },
            success: function(response) {
                var quote_partners = response.data;
                var quote_partners_html = generateQuotePartnersList(quote_partners, quote_id_customer_selected_for_close);
                $("#quote-close-modal #partner-list").html(quote_partners_html);
            }
        });
    }

    function generateQuotePartnersList(partners) {
        var partnerHtml = '';
        if (partners.length > 0) {
            $.each(partners, function (index, partner) {
                partnerHtml += `<tr data-partner-id="${partner.id}">
                                    <td>${partner.name}</td>
                                    <td align="right">
                                        <button class="btn btn-theme-black btn-sm rate-partner" data-id="${partner.id}">Which business did you go with?</button>
                                    </td>
                                </tr>
                                <tr class="rating-row" id="rating-row-${partner.id}" style="display: none;">
                                    <td colspan="2">
                                        <div class="rating-section">
                                            <form class="rating-form" data-partner-id="${partner.id}">
                                                <input type="hidden" name="quote_id" value="${quote_id_customer_selected_for_close}">
                                                <input type="hidden" name="partner_id" value="${partner.id}">
                                                <label>Rating:</label>
                                                <div class="star-rating" data-partner-id="${partner.id}">
                                                    <span class="fa fa-star-o" data-rating="1"></span>
                                                    <span class="fa fa-star-o" data-rating="2"></span>
                                                    <span class="fa fa-star-o" data-rating="3"></span>
                                                    <span class="fa fa-star-o" data-rating="4"></span>
                                                    <span class="fa fa-star-o" data-rating="5"></span>
                                                </div>
                                                <input type="hidden" class="rating-value" name="rating" data-partner-id="${partner.id}" value="0">
                                                <label>Review:</label>
                                                <textarea class="form-control review-text" name="review" data-partner-id="${partner.id}" rows="3"></textarea>
                                                <br>
                                                <button type="submit" class="btn btn-theme-primary submit-rating">Submit</button>
                                                <button type="button" data-id="${partner.id}" class="btn cancel-rating">Cancel</button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>`;
            });
        } else {
            partnerHtml += `<tr>
                                    <td colspan="2" align="center">No providers connected!</td>
                                </tr>`;
        }
        return partnerHtml;
    }    

    // Show Rating Section when clicking the "Rate" button
    $(document).on("click", "#quote-close-modal .rate-partner", function () {
        var partnerId = $(this).data("id");
        $(".rating-row").hide(); // Hide other rating sections
        $("#rating-row-" + partnerId).fadeIn();
    });

    // Star Rating Selection
    $(document).on("click", "#quote-close-modal .star-rating span", function () {
        var rating = $(this).data("rating");
        var partnerId = $(this).parent().data("partner-id");

        // Update the rating value
        $(".rating-value[data-partner-id='" + partnerId + "']").val(rating);

        // Update star icons
        $(this).siblings().removeClass("fa-star").addClass("fa-star-o");
        $(this).prevAll().addBack().removeClass("fa-star-o").addClass("fa-star");
    });

    // Submit Rating via AJAX
    $(document).on("submit", "#quote-close-modal .rating-form", function (e) {
        e.preventDefault(); // Prevent default form submission
    
        var form = $(this);
        var formData = form.serialize(); // Serialize form data

        var partnerId = $(this).find('input[name="partner_id"]').val();
    
        $.ajax({
            type: "POST",
            url: ajax_object.ajax_url, // Use localized admin-ajax.php URL
            data: formData + "&action=handle_partner_rating_submission&security=" + ajax_object.nonce, // Add action and nonce
            beforeSend: function () {
                form.find(".submit-rating").prop("disabled", true).text("Submitting...");
                form.find('.error-messages').remove();
            },
            success: function (response) {
                if (response.success) {
                    // Hide rating row and show success message
                    $(`#rating-row-${partnerId}`).fadeOut();
                    toastr.success(response.data.message); // Or show in a success div
                    location.reload();
                } else {
                    // Check if errors exist
                    if (response.data.errors && response.data.errors.length > 0) {
                        let errorHtml = `<div class="error-messages alert alert-danger" style="margin-top: 15px;">`;
                        response.data.errors.forEach(error => {
                            errorHtml += `<p>${error}</p>`;
                        });
                        errorHtml += `</div>`;

                        console.log(errorHtml)
        
                        // Append error messages after submit button inside the rating row
                        $(form).append(errorHtml);
                    }
                }
            },
            error: function (xhr, status, error) {
                console.error("AJAX Error:", status, error);
            },
            complete: function () {
                form.find(".submit-rating").prop("disabled", false).text("Submit");
            }
        });
    });    

    // Cancel Rating
    $(document).on("click", ".cancel-rating", function () {
        var partnerId = $(this).data("id");
        $("#rating-row-" + partnerId).fadeOut();
    });
});