jQuery(document).ready(function($) {
    $('.toggle-subscription').click(function() {
        var button = $(this);
        var userId = button.data('id');
        var newStatus = button.data('status');

        $.ajax({
            url: ajax_object.ajax_url,
            type: 'POST',
            data: {
                action: 'toggle_subscription',
                user_id: userId,
                status: newStatus,
            },
            success: function(response) {
                if (response.success) {
                    location.reload();
                } else {
                    alert('Failed to update subscription status.');
                }
            }
        });
    });
});
