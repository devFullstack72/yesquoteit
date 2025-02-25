<div class="container hotel-finder-page">
    <h2 class="htlfndr-section-title bigger-title">Quote Requests</h2>
    <div class="htlfndr-section-under-title-line"></div>
    <div class="table-wrap">
    <button id="deleteSelected" class="btn btn-danger" style="display: none; float: right; margin-bottom: 10px;">
       <i class="fa fa-trash"></i> Delete Selected
    </button>

    <table class="table table-responsive-xl">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>Email</th>
                <th>Enquiry Name</th>
                <th>Status</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($customer_quotes)) : ?>
                <?php foreach ($customer_quotes as $customer_quote) : ?>
                    <tr>
                        <td><input type="checkbox" class="quote-checkbox" value="<?php echo $customer_quote->lead_quote_id; ?>"></td>
                       
                        <td>
                            <span><?php echo esc_html($customer_quote->name); ?><br></span>
                            <span><?php echo esc_html($customer_quote->email); ?></span><br>
                            <span><?php echo esc_html($customer_quote->phone); ?><br></span>
                            <span class="text-muted" style="font-size: 11px;"><?php echo esc_html(date('d.m.Y H:i a', strtotime($customer_quote->created_at))); ?></span>
                        </td>
                        
                        <td>
                            <a href="javascript:void(0);" 
                            class="open-lead-modal" 
                            data-quote='<?php echo esc_attr($customer_quote->quote_data); ?>'> <!-- Store JSON -->
                                <?php echo esc_html($customer_quote->lead_name); ?>
                            </a>
                        </td>

                        <td>
                            <span class="badge <?php 
                                echo ($customer_quote->status == 'New Lead') ? 'badge-success' : 
                                    (($customer_quote->status == 'Viewed') ? 'badge-warning' : 'badge-primary'); 
                            ?>">
                                <?php echo esc_html($customer_quote->status); ?>
                            </span>
                        </td>

                        
                        <td>
                            <button class="btn btn-primary text-xs open-email-modal" 
                                    data-email="<?php echo esc_attr($customer_quote->email); ?>"
                                    data-quote_id="<?php echo esc_attr($customer_quote->lead_quote_id); ?>">
                                    <i class="fa fa-envelope"></i> Send Email
                            </button>

                            <button type="button" class="close delete-quote" data-id="<?php echo $customer_quote->lead_quote_id; ?>" data-dismiss="alert" aria-label="Close">
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
        <h4>Quote Data</h4>
        <div id="quote_details"></div> <!-- Dynamic Key-Value Pairs -->
        <button class="btn btn-primary close-lead-modal">Close</button>
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
            $("#deleteSelected").show();
        } else {
            $("#deleteSelected").hide();
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
});

jQuery(document).ready(function($) {
    $(".open-lead-modal").click(function() {
        var quoteId = $(this).closest("tr").find(".quote-checkbox").val();
        var quoteData = $(this).data("quote");
        var quoteObj = typeof quoteData === "string" ? JSON.parse(quoteData) : quoteData;

        $("#quote_details").html("");

        $.each(quoteObj, function(key, value) {
            $("#quote_details").append("<p><strong>" + key + ":</strong> " + value + "</p>");
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



</script>

<style>
.htlfndr-under-header{display: none;}
#emailModal { z-index: 999999; }#leadModal { z-index: 999999; }
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

.badge-success {
    background-color: #cff6dd !important; /* Ensure it overrides */
    color: #1fa750;
}

.badge-warning {
    background-color: #fdf5dd !important; /* Ensure it overrides */
    color: #cfa00c;
}

.badge-primary {
    background-color:rgb(121, 193, 237) !important; /* Ensure it overrides */
    color:rgb(255, 255, 255);
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


</style>
