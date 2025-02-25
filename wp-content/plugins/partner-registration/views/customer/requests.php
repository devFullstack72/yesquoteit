<div class="container hotel-finder-page">
    <h2 class="htlfndr-section-title bigger-title">Quote Requests</h2>
    <div class="htlfndr-section-under-title-line"></div>
    <div class="table-wrap">
    <button id="deleteSelected" class="btn btn-danger" style="display: none; float: right; margin-bottom: 10px;">
        Delete Selected
    </button>

    <table class="table table-responsive-xl">
        <thead>
            <tr>
                <th><input type="checkbox" id="selectAll"></th>
                <th>Email</th>
                <th>enquiry name</th>
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
                data: { action: "delete_multiple_customer_quotes", ids: selectedIds },
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

        $.each(quoteObj, function(key, value) {
            $("#quote_details").append("<p><strong>" + key + ":</strong> " + value + "</p>");
        });

        $("#leadModal").show();
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

.table td a {
    color: black;
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
</style>
