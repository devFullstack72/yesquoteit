<div class="container hotel-finder-page">
    <h2 class="htlfndr-section-title bigger-title">Quote Requests</h2>
    <div class="htlfndr-section-under-title-line"></div>
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

                <button id="deleteSelected" class="btn btn-danger" style="margin-bottom: 10px;">
                <i class="fa fa-trash"></i> Delete
                </button>
            </div>
            <div>
                <!-- <?php if ($is_archived) : ?>
                <a href="<?php echo home_url() . '/customer-requests' ?>" class="btn btn-warning" style="color: white;">Open Non Archived</a>
                <?php else : ?>
                    <a href="<?php echo home_url() . '/customer-requests/?is_archived=1' ?>" class="btn btn-warning" style="color: white;">Open Archived</a>
                <?php endif; ?> -->
            </div>
        </div>
    </div>

    <table class="table table-responsive-xl">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>Quote Request</th>
                <th>Contact Details</th>
                <th>Inbox</th>
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
                       
                        <?php $image = get_the_post_thumbnail_url($customer_quote->lead_id, 'large'); ?>

                        <td>
                            <div class="d-flex align-items-center" style="display: flex; align-items: center; gap: 10px;">
                                <?php if (!empty($image)) { ?>
                                    <div class="img" style="background-image: url('<?php echo esc_url($image); ?>');"></div>
                                <?php } ?>

                                <div class="email" style="white-space: nowrap;">
                                    <a href="javascript:void(0);" 
                                        class="open-lead-modal" 
                                        data-quote='<?php echo esc_attr($customer_quote->quote_data); ?>'> 
                                        <?php echo esc_html($customer_quote->lead_name); ?>
                                    </a>
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
                                    <br>
                                    <label class="badge-theme badge-theme-<?php echo $customer_urgency['class'] ?>"><?php echo $customer_urgency['label'] ?></label>
                                </div>
                            </div>
                        </td>
                        
                        <td>
                            <span><?php echo esc_html($customer_quote->name); ?><br></span>
                            <span><?php echo esc_html($customer_quote->email); ?></span><br>
                            <span><?php echo esc_html($customer_quote->phone); ?><br></span>
                            <span class="text-muted" style="font-size: 11px;"><?php echo esc_html(date('d.m.Y H:i a', strtotime($customer_quote->lead_created_at))); ?></span>
                        </td>

                        <td>
                            <?php
                                
                                $status = get_quote_message_status($customer_quote->lead_quote_id);
                                $message_count = $status->total_messages ?? 0;
                                $unread_count = $status->unread_messages ?? 0;
                                $total_chat_partners = $status->total_chat_partners ?? 0;
                                $total_unread_chats = $status->total_unread_chats;
                                
                                
                                // Determine color class
                                if ($total_chat_partners == 0) {
                                    $color_class = 'theme-text';
                                } elseif ($total_unread_chats > 0) {
                                    $color_class = 'green-alert';
                                } else {
                                    $color_class = 'yellow-alert';
                                }
                            ?>
                            
                           
                            <span class="message-count <?php echo $color_class; ?>" 
                                onclick="openChatPopup(<?php echo $customer_quote->lead_quote_id; ?>)">
                                <i class="fa fa-envelope"></i>
                                Messages received <?php echo $total_chat_partners; ?>
                            </span>
                        </td>
                        
                        <td>
                            <?php if ($customer_quote->quote_closed == 1): ?>
                                <label class="btn btn-theme-gray solid">Closed</label>
                            <?php else: ?>
                            <button type="button" class="btn btn-theme-black close-quote-by-customer" data-id="<?php echo $customer_quote->lead_quote_id; ?>">Close Quote</button>
                            <?php endif; ?>
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


<!-- Lead Info Modal -->
<div id="leadModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close close-lead-modal">&times;</span>
        <h4>Quote Request</h4>
        <div id="quote_details"></div> <!-- Dynamic Key-Value Pairs -->
    </div>
</div>

<div id="partnerChatModal" class="modal">
    <div class="modal-content">
        <span class="close partner-chat-close">&times;</span>
        <h4>Quote Chat</h4>
        <div id="chatContainer"></div> <!-- Chats will be inserted here -->
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

<!-- Partner List Modal -->
<div id="quote-close-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg"> <!-- Large modal -->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Mark as Closed</h4>
            </div>
            <div class="modal-body">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Which business did you go with?</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody id="partner-list">
                        <tr id="loading-spinner">
                            <td colspan="2" class="text-center">
                                <i class="fa fa-spinner fa-spin fa-2x"></i> Loading...
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>



<script>

jQuery(document).ready(function($) {

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
                data: { action: "delete_multiple_customer_quotes", ids: selectedIds },
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
                data: { action: "archive_multiple_customer_quotes", ids: selectedIds, is_archived: is_archived },
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
                data: { action: "delete_customer_quote", id: quoteId },
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

        // Create the table structure
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
        window.location.href = "<?php echo home_url(); ?>/customer-requests/?is_archived=1";
    } else if (selected === "open") {
        archiveCheckbox.checked = false; // Deselect Archive
        window.location.href = "<?php echo home_url(); ?>/customer-requests";
    }
}

var ajaxurl = "<?php echo admin_url('admin-ajax.php'); ?>";
var chat_send_action = 'send_to_partner_message';

function openChatPopup(leadId) {
    fetch(ajaxurl + "?action=get_chat_details&lead_id=" + leadId)
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById("chatContainer").innerHTML = data.data.html; // Inject HTML into container
            document.getElementById("partnerChatModal").style.display = "block"; // Show popup
        }
    })
    .catch(error => console.error('Error:', error));
}
</script>

<script src="<?php echo get_template_directory_uri(); ?>/js/chat-message.js"></script>

<script>
    var ajax_object = {
        ajax_url: "<?php echo admin_url('admin-ajax.php'); ?>",
        nonce: "<?php echo wp_create_nonce('get_quote_partners_nonce'); ?>"
    };
</script>

<script src="<?php echo get_template_directory_uri(); ?>/js/customer/requests.js"></script>


<style>
.htlfndr-under-header{display: none;}
#emailModal { z-index: 999999; }#leadModal { z-index: 999999; }#chatModal{ z-index: 999999;}#partnerChatModal{ z-index: 999999;}
.open-email-modal { margin-top: 0px; }
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

.message-count {
    cursor: pointer;
    padding: 5px;
    border-radius: 5px;
}

.chat-item {
    display: flex;
    align-items: center;
    padding: 10px;
    border-bottom: 1px solid #ddd;
}

.business-logo {
    width: 40px;
    height: 40px;
    border-radius: 50%;
    margin-right: 10px;
}

.business-name {
    font-weight: bold;
    flex-grow: 1;
}

.view-quote {
    padding: 5px 10px;
    border-radius: 5px;
    cursor: pointer;
}

.green-button {
    background-color: green;
    color: white;
}

.yellow-button {
    background-color: #fcf8e3;
    color: #cfa00c;
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
    width: 80px;
    height: 80px;
    background: url(../wp-includes/images/spinner-overlay.gif) center no-repeat;
    background-size: contain; /* Ensures image scales properly */
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
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
</style>
