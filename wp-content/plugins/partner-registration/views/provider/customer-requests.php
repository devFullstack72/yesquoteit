<div class="container hotel-finder-page">
    <h2 class="htlfndr-section-title bigger-title">Customer Requests</h2>
    <div class="htlfndr-section-under-title-line"></div>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Lead</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Date</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($customer_quotes)) : ?>
                <?php foreach ($customer_quotes as $customer_quote) : ?>
                    <tr>
                        <td>
                        <a href="<?php echo esc_url(get_permalink($customer_quote->lead_id)); ?>" target="_blank">
                            <?php echo esc_html($customer_quote->lead_name); ?></td>
                        <td><?php echo esc_html($customer_quote->name); ?></td>
                        <td><?php echo esc_html($customer_quote->email); ?></td>
                        <td><?php echo esc_html($customer_quote->phone); ?></td>
                        <td><?php echo esc_html(date('d.m.Y H:i a', strtotime($customer_quote->created_at))); ?></td>
                        <td>
                            <button class="btn btn-primary text-xs open-email-modal" 
                                    data-email="<?php echo esc_attr($customer_quote->email); ?>"
                                    data-name="<?php echo esc_attr($customer_quote->name); ?>">
                                Send Email to Customer
                            </button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php else : ?>
                <tr>
                    <td colspan="5" class="text-center">No customer requests found.</td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<!-- Email Modal -->
<div id="emailModal" class="modal" style="display: none;">
    <div class="modal-content">
        <span class="close">&times;</span>
        <form id="sendEmailForm">
            <input type="hidden" name="email" id="customer_email">
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

<script>
jQuery(document).ready(function($) {
    // Open Modal
    $(".open-email-modal").click(function() {
        var email = $(this).data("email");
        
        // Set customer email and reset form fields
        $("#customer_email").val(email);
        $("#email_subject").val(""); // Reset subject
        $("#email_message").val(""); // Reset message
        $("#emailStatus").text("");  // Clear status message

        $("#emailModal").show();
    });

    // Close Modal
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
});
</script>

<style>
#emailModal{z-index: 999999;} .open-email-modal{margin-top: 0px;} .modal { display: none; position: fixed; left: 0; top: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.5); }
.modal-content { background: #fff; margin: 15% auto; padding: 20px; width: 40%; border-radius: 5px; }
.close { float: right; font-size: 28px; cursor: pointer; }
</style>