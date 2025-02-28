// Open Chat Modal
function openChat(partner_id, customer_id, lead_id, view = 'customer') {

    // if(view == 'customer'){
    //     $("#partnerChatModal").hide();
    // }

    $("#chatModal").show();
    
    $("#partner_id").val(partner_id);
    $("#customer_id").val(customer_id);
    $("#lead_id").val(lead_id);
    $("#chat_messages").html(""); // Clear old messages

    // Show loading spinner
    $("#chat_messages").html("<div class='chat-loading'>Loading...</div>");

    // Load previous messages
    $.ajax({
        type: "POST",
        url: ajaxurl,
        data: {
            action: "load_chat_messages",
            partner_id: partner_id,
            customer_id: customer_id,
            lead_id: lead_id,
            view: view
        },
        success: function(response) {
            $("#chat_messages").html(response.data);
            $("#partner_id").val(partner_id);
            $("#customer_id").val(customer_id);
            $("#lead_id").val(lead_id);
        },
        complete: function () {
            $(".chat-loading").remove(); // Remove loading spinner after loading messages
        }
    });
}

jQuery(document).ready(function($) {

    // Close Chat Modal
    $(".partner-chat-close").click(function() {
        $("#partnerChatModal").hide();
    });

    $(".close-chat-modal").click(function() {
        $("#chatModal").hide();
    });

    // Send Message via AJAX
    $("#sendMessage").click(function () {
        var partner_id = $("#partner_id").val();
        var customer_id = $("#customer_id").val();
        var lead_id = $("#lead_id").val();
        var message = $("#chat_message").val().trim();

        if (message === "") {
            alert("Message cannot be empty.");
            return;
        }

        // Disable button to prevent multiple clicks
        $("#sendMessage").text("Sending...").prop("disabled", true);

        $.ajax({
            type: "POST",
            url: ajaxurl,
            data: {
                action: chat_send_action,
                partner_id: partner_id,
                customer_id: customer_id,
                lead_id: lead_id,
                message: message
            },
            success: function (response) {
                if (response.success) {
                    var timestamp = new Date().toLocaleTimeString([], { hour: '2-digit', minute: '2-digit' });

                    var newMessage = `
                        <div class='chat-message sent'>
                            <div class='chat-bubble'>
                                <strong>You</strong>
                                <p>${message}</p>
                                <span class='chat-time'>${timestamp}</span>
                            </div>
                        </div>
                    `;

                    // Check if .chat-container exists
                    if ($("#chat_messages").find('.chat-container').length === 0) {
                        $("#chat_messages").append("<div class='chat-container'></div>");
                    }

                    // Append message inside .chat-container
                    $("#chat_messages").find('.chat-container').append(newMessage);
                    $("#chat_message").val(""); // Clear input

                    // Auto-scroll to latest message
                    $("#chat_messages").scrollTop($("#chat_messages")[0].scrollHeight);

                    sendChatNotification(response.data.chat_message_id, response.data.customer_id, response.data.partner_id, response.data.message_text, response.data.view);
                } else {
                    alert("Message failed to send.");
                }
            },
            complete: function () {
                $("#sendMessage").text("Send").prop("disabled", false);
            }
        });

    });
});

function sendChatNotification(chat_message_id, customer_id, partner_id, message_text, view) {
    $.ajax({
        type: "POST",
        url: ajaxurl,
        data: {
            action: "send_chat_notification",
            chat_message_id: chat_message_id,
            customer_id: customer_id,
            partner_id: partner_id,
            message: message_text,
            view: view,
        },
        success: function (response) {
            console.log("Notification sent successfully.");
        },
        error: function () {
            console.log("Failed to send notification.");
        }
    });
}