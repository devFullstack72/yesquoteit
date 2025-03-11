<div class="container hotel-finder-page">
    <!-- <h2 class="htlfndr-section-title bigger-title">Quote Requests</h2> -->
    <!-- <div class="htlfndr-section-under-title-line"></div> -->
    <div class="table-wrap">

    <div class="row">
        <div class="col-md-12 text-right" style="display: flex;justify-content: end;">
            <div class="multi-action-buttons" style="display: none;">
                <?php if ($is_archived) : ?>
                    <button class="btn btn-warning archived-multi" data-status="0" style="margin-bottom: 10px;">
                    <i class="fa fa-arrow-down"></i> Remove from Archived
                    </button>
                <?php else : ?>
                    <button class="btn btn-warning archived-multi" data-status="1" style="margin-bottom: 10px;">
                    <i class="fa fa-arrow-down"></i> Archive
                    </button>
                <?php endif; ?>

                <!-- <button id="deleteSelected" class="btn btn-danger" style="margin-bottom: 10px;">
                <i class="fa fa-trash"></i> Delete
                </button> -->

                <button id="deleteSelected" class="btn btn-danger" style="margin-bottom: 10px;">
                    <i class="fa fa-trash"></i> Delete
                </button>
                    
            </div>
            <div>
                <!-- <?php if ($is_archived) : ?>
                <a href="<?php echo home_url() . '/partner-customer-requests' ?>" class="btn btn-warning" style="color: white;">Open Non Archived</a>
                <?php else : ?>
                    <a href="<?php echo home_url() . '/partner-customer-requests/?is_archived=1' ?>" class="btn btn-warning" style="color: white;">Open Archived</a>
                <?php endif; ?> -->
            </div>
        </div>
    </div>

    <table class="table table-responsive-xl custom-mt-15">
        <thead>
            <tr>
                <th>
                    <div class="d-flex align-items-center" style="display: flex; align-items: center; gap: 10px;">
                        <div class="profile-img" style="background-image: url('<?php echo $provider_details->business_logo ?>');"></div>
                        <h3 style="color:black;"><?php echo $provider_details->business_trading_name ?>
                            <div style="font-size: 12px; margin-top: 10px; text-align: right;">
                                <i class="fa fa-check-circle" style="color: #08c1da;font-size: 15px;"></i>
                                 <i>Verified member</i>
                             </div>
                        </h3>
                        
                    </div>
                </th>
                <th colspan="5" style="float:right;vertical-align:middle;">
                    <a class="btn btn-default text-xs" target="_blank" href="<?php echo esc_url(home_url()); ?>/provider/<?php echo $provider_details->id ?>"><i class="fa fa-eye"></i> Public Profile</a>
                    <a class="btn btn-default text-xs" href="<?php echo esc_url(home_url()); ?>/partner-customer-requests"><i class="fa fa-bullhorn"></i> Your Quotes</a>
                    <a class="btn btn-default text-xs" href="<?php echo esc_url(site_url('/partner-profile')); ?>"><i class="fa fa-pencil"></i> Edit Profile</a>
                </th>
            </tr>
        </thead>
    </table>


    <table class="table table-responsive-xl">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>Customer Details</th>
                <th>Quote Request</th>
                <th>Status</th>
                <th>Messages</th>
                <th>
                    <input type="checkbox" <?php if (!$is_archived){ echo "checked"; } ?> id="open" onclick="handleCheckboxSelection('open')"> Open
                    <input type="checkbox" <?php if ($is_archived){ echo "checked"; } ?>  id="archive" onclick="handleCheckboxSelection('archive')"> Archive
                </th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($customer_quotes)) : ?>
                <?php foreach ($customer_quotes as $customer_quote) : ?>
                    <tr>
                        <td><input type="checkbox" class="quote-checkbox" value="<?php echo $customer_quote->lead_quote_id; ?>"></td>
                       
                        <td>
                            <span><?php echo esc_html($customer_quote->name); ?><br></span>
                            <span>
                            <a href="mailto:<?php echo esc_attr($customer_quote->email); ?>">
                                <?php echo esc_html($customer_quote->email); ?>
                            </a>
                            </span><br>
                            <span><?php echo esc_html($customer_quote->phone); ?><br></span>
                            <span class="text-muted" style="font-size: 11px;"><?php echo esc_html(date('d.m.Y H:i a', strtotime($customer_quote->lead_created_at))); ?></span>
                        </td>
                        
                        
                        <td>
                            <?php $image = get_the_post_thumbnail_url($customer_quote->lead_id, 'large'); ?>
                            <div class="d-flex align-items-center" style="display: flex; align-items: center; gap: 10px;">
                                <?php if (!empty($image)) { ?>
                                    <div class="img" style="background-image: url('<?php echo esc_url($image); ?>');"></div>
                                <?php } ?>

                                <div class="email" style="white-space: nowrap;">
                                    <a href="javascript:void(0);" 
                                        class="open-lead-modal text-black" 
                                        data-quote='<?php echo esc_attr($customer_quote->quote_data); ?>'> 
                                        <?php echo esc_html($customer_quote->lead_name); ?>
                                    </a>
                                    <div style="display: flex; gap: 2px;">
                                        <button class="btn btn-sm btn-theme-primary open-lead-modal" 
                                        data-quote='<?php echo esc_attr($customer_quote->quote_data); ?>'><i class="fa fa-eye"></i> Details</button>
                                        <?php
                                        $quote_details = !empty($customer_quote->quote_data) ? json_decode($customer_quote->quote_data, TRUE) : '';
                                        $customer_urgency = [
                                            'class' => getCustomerUrgencyClass('Low'),
                                            'label' => 'Low'
                                        ];
                                        if (isset($quote_details['customer-urgency'])) {
                                            $customer_urgency_value = $quote_details['customer-urgency']['value'] ?? 'Low';
                                            $customer_urgency = [
                                                'class' => getCustomerUrgencyClass($customer_urgency_value),
                                                'label' => $customer_urgency_value
                                            ];
                                        }
                                        ?>
                                        <!-- <br>
                                        <label class="badge-theme badge-theme-<?php echo $customer_urgency['class'] ?> my-auto"><?php echo $customer_urgency['label'] ?></label> -->
                                    </div>
                                </div>
                            </div>
                        </td>

                        <td>
                            <div class="badge-theme badge-theme-<?php 
                                echo ($customer_quote->status == 'New Lead') ? 'success' : 
                                    (($customer_quote->status == 'Viewed') ? 'warning' : 'primary'); 
                            ?>" style="min-width: 65px; text-align: center;">
                                <?php echo esc_html($customer_quote->status); ?>
                            </div>
                        </td>
                        
                        <td>
                             <button style="display: none;" class="btn btn-primary text-xs open-email-modal" 
                                    data-email="<?php echo esc_attr($customer_quote->email); ?>"
                                    data-quote_id="<?php echo esc_attr($customer_quote->lead_quote_id); ?>">
                                    <i class="fa fa-envelope"></i> Send Email
                            </button>

                            <?php
                                $partner_id = $customer_quote->provider_id;
                                $customer_id = $customer_quote->customer_id;
                                $lead_quote_id = $customer_quote->l_quote_id;

                                $has_messages = has_chat_messages($partner_id, $customer_id, $lead_quote_id);
                            ?>

                            <button class="btn btn-primary text-xs open-chat-modal"  
                                onclick="openChat(<?php echo $customer_quote->provider_id; ?>, <?php echo $customer_quote->customer_id; ?>, <?php echo $customer_quote->l_quote_id; ?>, 'partner')">
                                <i class="fa fa-comments"></i> <?php echo $has_messages ? 'View Messages' : 'Send Message'; ?>
                            </button>
                            
                        </td>
                        <td>
                            <button type="button" class="btn btn-theme-light-danger delete-quote" data-id="<?php echo $customer_quote->lead_quote_id; ?>" data-dismiss="alert" aria-label="Close">
				            	<span aria-hidden="true"><i class="fa fa-close"></i></span>
				          	</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="7" class="text-center">No quote requests found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
    </div>
</div>

<!-- Chat Modal -->
<div id="chatModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close close-chat-modal">&times;</span>
        <h4>Quote Chat</h4>
        <div id="chat_messages"></div> <!-- Chat messages load here -->
        <input type="hidden" id="partner_id">
        <input type="hidden" id="customer_id">
        <input type="hidden" id="lead_id">
        <div class="form-group">
            <textarea id="chat_message" class="form-control" rows="2" placeholder="Type a message..."></textarea>
        </div>
        <button id="sendMessage" class="btn btn-success">Send</button>
    </div>
</div>


<!-- Email Modal -->
<div id="emailModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <form id="sendEmailForm">
            <input type="hidden" name="email" id="customer_email">
            <input type="hidden" name="quote_id" id="quote_id">
            <div class="form-group">
                <label for="email_subject">Subject:</label>
                <input type="text" name="subject" id="email_subject" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="email_message">Message:</label>
                <textarea name="message" id="email_message" class="form-control" rows="4" required></textarea>
            </div>
            <button type="submit" class="btn btn-success">Send Email</button>
        </form>
        <p id="emailStatus"></p>
    </div>
</div>

<!-- Lead Info Modal -->
<div id="leadModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close close-lead-modal">&times;</span>
        <h4>Quote Request</h4>
        <div id="quote_details"></div> <!-- Dynamic Key-Value Pairs -->
    </div>
</div>



<script>
jQuery(document).ready(function($) {
    // Open Email Modal
    $(".open-email-modal").click(function() {
        var email = $(this).data("email");
        var quote_id = $(this).data("quote_id");
        $("#customer_email").val(email);
        $('#quote_id').val(quote_id);
        $("#email_subject").val("");
        $("#email_message").val("");
        $("#emailStatus").text("");
        $("#emailModal").show();
    });
    
    // Close Email Modal
    $(".close").click(function() {
        $("#emailModal").hide();
    });

    // Send Email via AJAX
    $("#sendEmailForm").submit(function(e) {
        e.preventDefault();
        
        var formData = $(this).serialize();
        $("#emailStatus").text("Sending...");

        $.ajax({
            type: "POST",
            url: "<?php echo admin_url('admin-ajax.php'); ?>",
            data: formData + "&action=send_customer_email",
            success: function(response) {
                $("#emailStatus").text(response);
            }
        });
    });

    // Select All Checkboxes
    $("#selectAll").click(function() {
        $(".quote-checkbox").prop("checked", this.checked);
        toggleDeleteButton();
    });

     // Toggle delete button visibility
     $(".quote-checkbox").change(function() {
        toggleDeleteButton();
    });

    function toggleDeleteButton() {
        var selectedCount = $(".quote-checkbox:checked").length;
        if (selectedCount > 0) {
            $(".multi-action-buttons").show();
        } else {
            $(".multi-action-buttons").hide();
        }
    }

    // Delete Selected Quotes
    $("#deleteSelected").click(function() {
        var selectedIds = $(".quote-checkbox:checked").map(function() {
            return $(this).val();
        }).get();

        if (selectedIds.length === 0) {
            alert("Please select at least one quote to delete.");
            return;
        }

        if (confirm("Are you sure you want to delete the selected quotes?")) {
            $.ajax({
                type: "POST",
                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                data: { action: "delete_multiple_quotes", ids: selectedIds },
                success: function(response) {
                    location.reload();
                }
            });
        }
    });

    // Delete Selected Quotes
    $(".delete-quote").click(function() {
        var quoteId = $(this).data("id");
        if (confirm("Are you sure you want to delete this request?")) {
            $.ajax({
                type: "POST",
                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                data: { action: "delete_partner_quote", id: quoteId },
                success: function(response) {
                    location.reload();
                }
            });
        }
    });

    $(".archived-multi").click(function() {
        var selectedIds = $(".quote-checkbox:checked").map(function() {
            return $(this).val();
        }).get();

        var is_archived = $(this).data('status');

        var flag_state = is_archived == 1 ? 'archive' : 'unarchive';

        if (selectedIds.length === 0) {
            alert("Please select at least one quote to " + flag_state + ".");
            return;
        }

        if (confirm("Are you sure you want to " + flag_state + " the selected quotes?")) {
            $.ajax({
                type: "POST",
                url: "<?php echo admin_url('admin-ajax.php'); ?>",
                data: { action: "archive_multiple_partner_quotes", ids: selectedIds, is_archived: is_archived },
                success: function(response) {
                    location.reload();
                }
            });
        }
    });
});


jQuery(document).ready(function($) {
    $(".open-lead-modal").click(function() {
        var quoteId = $(this).closest("tr").find(".quote-checkbox").val();
        var quoteData = $(this).data("quote");
        var quoteObj = typeof quoteData === "string" ? JSON.parse(quoteData) : quoteData;

        $("#quote_details").html("");

        // $.each(quoteObj, function(key, value) {
        //     $("#quote_details").append("<p><strong>" + key + ":</strong> " + value + "</p>");
        // });

        $("#quote_details").html('<table class="quote-table"><tbody></tbody></table>');

        $.each(quoteObj, function(key, item) {
            if (typeof item.label !== 'undefined') {
                if (!(['google_places_form_street_number', 'google_places_form_street', 'google_places_form_latitude', 'google_places_form_longitude'].includes(key)) && !item.label.includes('is_lead') && !item.label.startsWith('cf7mls_step')) {
                    $(".quote-table tbody").append(
                        "<tr><td><strong>" + item.label + "</strong></td><td>" + item.value + "</td></tr>"
                    );
                }
            }
        });

        $("#leadModal").show();

        // Send AJAX request to update status
        $.ajax({
            type: "POST",
            url: "<?php echo admin_url('admin-ajax.php'); ?>",
            data: { action: "update_quote_status", id: quoteId },
            success: function(response) {
                if (response.success) {
                    var statusElement = $(".quote-checkbox[value='" + quoteId + "']").closest('tr').find('.status_label');
                    statusElement.text("Viewed").css("color", "orange").removeClass("new other").addClass("viewed");
                } else {
                    alert("Failed to update status.");
                }
            }
        });
    });

    $(".close-lead-modal").click(function() {
        $("#leadModal").hide();
    });
});

function handleCheckboxSelection(selected) {
    let archiveCheckbox = document.getElementById("archive");
    let openCheckbox = document.getElementById("open");

    if (selected === "archive") {
        openCheckbox.checked = false; // Deselect Open
        window.location.href = "<?php echo home_url(); ?>/partner-customer-requests/?is_archived=1";
    } else if (selected === "open") {
        archiveCheckbox.checked = false; // Deselect Archive
        window.location.href = "<?php echo home_url(); ?>/partner-customer-requests";
    }
}

var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
var chat_send_action = 'send_chat_message';

</script>
<script src="<?php echo get_template_directory_uri(); ?>/js/chat-message.js"></script>
<style>
.htlfndr-under-header{display: none;}
#emailModal { z-index: 999999; }#leadModal { z-index: 999999; }#chatModal { z-index: 999999; }
.open-email-modal, .delete-quote { margin-top: 0px; }
.modal { display: none; position: fixed; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
.modal-content { background: #fff; margin: 15% auto; padding: 20px; width: 40%; border-radius: 5px; }
.close { float: right; font-size: 28px; cursor: pointer; }
.status_label.new { color: green; }
.status_label.viewed { color: orange; }
.status_label.other { color: blue; }


body {background-color: #f8f9fd;}
.table td, .table thead, .table th {
    border: none !important;
}

.table th {
    color: grey;
}



/* .table td, .table thead{border-bottom: 2px solid grey !important;} */
.table tbody td .close span {
    font-size: 12px;
    color: #dc3545;
}

.table thead tr {
    background: #fff;
    border-bottom: 4px solid #eceffa;
}

.table tbody tr {
    background: #fff;
    margin-bottom: 10px !important;;
    border-bottom: 4px solid #f8f9fd !important;;
}

.table tbody th, .table tbody td {
    border: none;
    padding: 30px;
    font-size: 14px;
    background: #fff;
    vertical-align: middle !important;
}

.table {
    width: 100%;
    margin-bottom: 1rem;
    color: #212529;
}

.btn-danger{
    background-color: #E4405F;
}

thead {
    display: table-header-group;
    vertical-align: middle;
    unicode-bidi: isolate;
    border-color: inherit;
}

.switch{
    border-radius: 3px;
}

.modal {
    display: none;
    position: fixed;
    left: 0;
    top: 0;
    width: 100%;
    height: 100%;
    background: rgba(0,0,0,0.5);
    overflow-y: auto; /* Enable scrolling */
}

.modal-content {
    background: #fff;
    margin: 5% auto; /* Adjusted to prevent overflow */
    padding: 20px;
    width: 60%; /* Adjust width if needed */
    max-height: 90vh; /* Prevents it from exceeding viewport height */
    overflow-y: auto; /* Enable internal scrolling */
    border-radius: 5px;
}

.open-chat-modal{
    margin-top: 0px;
}

.chat-container {
    display: flex;
    flex-direction: column;
    max-height: 400px;
    overflow-y: auto;
    padding: 15px;
    background: #f8f9fa;
    border-radius: 10px;
}

/* Chat message styling */
.chat-message {
    display: flex;
    align-items: flex-start;
    margin-bottom: 10px;
}

.sent {
    justify-content: flex-end;
}

.received {
    justify-content: flex-start;
}

/* Chat bubble */
.chat-bubble {
    max-width: 75%;
    padding: 10px 15px;
    border-radius: 18px;
    font-size: 14px;
    line-height: 1.4;
    box-shadow: 0px 2px 5px rgba(0, 0, 0, 0.1);
    position: relative;
}

.sent .chat-bubble {
    background: #007bff;
    color: white;
    border-bottom-right-radius: 4px;
}

.received .chat-bubble {
    background: #e9ecef;
    color: #333;
    border-bottom-left-radius: 4px;
}

/* Timestamp */
.chat-time {
    display: block;
    font-size: 12px;
    color: rgba(255, 255, 255, 0.8);
    text-align: right;
    margin-top: 5px;
}

.received .chat-time {
    color: rgba(0, 0, 0, 0.6);
}

/* No messages text */
.no-messages {
    text-align: center;
    font-size: 14px;
    color: #777;
    padding: 20px;
    font-style: italic;
}

#sendMessage{
    float: right;
}

.profile-img{
    width: 70px; 
    height: 70px; 
    background-size: cover;
    background-position: center;
    border-radius: 50%;
    display: inline-block;
}

.img {
    width: 40px; 
    height: 40px; 
    background-size: cover;
    background-position: center;
    border-radius: 50%;
    display: inline-block;
}

.chat-loading {
    display: flex;
    justify-content: center;
    align-items: center;
    height: 50px;
}

.chat-loading::after {
    content: "";
    width: 80px;  /* Adjust width */
    height: 80px; /* Adjust height */
    background: url(../wp-includes/images/spinner-overlay.gif) center no-repeat;
    background-size: contain; /* Ensures image scales properly */
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.custom-mt-15{
    margin-top:15px;
}

.btn-link{
    color:black;
    border-color:black;
}

.quote-table {
    width: 100%;
    border-collapse: collapse;
    margin-top: 10px;
}

.quote-table td {
    padding: 8px;
    border: 1px solid #ddd;
}

.quote-table tr:nth-child(even) {
    background-color: #f9f9f9;
}

.quote-table tr:hover {
    background-color: #f1f1f1;
}

/* Chat Bubble */
.chat-bubble {
    position: relative;
    background: #f1f0f0;
    padding: 10px;
    border-radius: 10px;
    max-width: 75%;
    margin: 5px 0;
}

.chat-message.sent .chat-bubble {
    background:rgb(71, 60, 130);
    align-self: flex-end;
}

.chat-message.received .chat-bubble {
    background: #fff;
    align-self: flex-start;
}

/* Link Preview - Compact Layout */
/* .link-preview {
    display: flex;
    align-items: center;
    background: #f9f9f9;
    border-radius: 8px;
    border: 1px solid #ddd;
    padding: 8px;
    margin-bottom: 8px;
    width: 100%;
    max-width: 300px;
    text-decoration: none;
    color: #000;
    overflow: hidden;
    white-space: nowrap;
} */


/* .preview-image {
    width: 40px;
    height: 40px;
    object-fit: cover;
    border-radius: 5px;
    margin-right: 10px;
    flex-shrink: 0;
}


.preview-text {
    flex-grow: 1;
    display: flex;
    flex-direction: column;
    justify-content: center;
    overflow: hidden;
}

.preview-text strong {
    font-size: 13px;
    color: #333;
    font-weight: bold;
    line-height: 1.2;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
}


.preview-text p {
    font-size: 12px;
    color: #666;
    margin-top: 2px;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
} */
</style>
