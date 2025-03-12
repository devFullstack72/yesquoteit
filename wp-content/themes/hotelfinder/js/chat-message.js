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
    $("#chat_messages").html("<div class='chat-loading'></div>");

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

            setChatLinksPreview();
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

    function _fnFormatMessage(text) {
        const urlPattern = /(https?:\/\/[^\s]+)/g; // Match URLs
        const formattedText = text
            .replace(urlPattern, '<a href="$1" target="_blank" rel="noopener noreferrer">$1</a>') // Convert URLs to links
            .replace(/\n/g, '<br>'); // Convert newlines to <br> for HTML
        
        return formattedText;
    }

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

                    var firstUrlMatch = message.match(/(https?:\/\/[^\s]+)/);
                    var firstUrl = firstUrlMatch ? firstUrlMatch[1] : "";
                    var linkPreview = firstUrl ? `<div class='link-preview-pending' data-url='${firstUrl}'></div>` : "";

                    var newMessage = `
                        <div class='chat-message sent'>
                            <div class='chat-bubble'>
                                <strong>You</strong>
                                ${linkPreview}
                                <p class='message-text'>${_fnFormatMessage(message)}</p>
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

                    setChatLinksPreview();

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

function setChatLinksPreview() {
    $(".link-preview-pending").each(function () {
        var previewElement = $(this);
        var url = previewElement.data("url");

        // Prevent duplicate processing
        if (!url || previewElement.data("processed") === true) return;

        var requestData = new FormData();
        requestData.append("action", "get_url_metadata");
        requestData.append("preview_url", url);

        $.ajax({
            url: ajaxurl,
            type: "POST",
            data: requestData,
            processData: false,
            contentType: false,
            dataType: "json",
            success: function (response) {
                if (!response.success) return;

                var data = response.data;

                var previewHTML = `
                    <div class="link-preview">
                        <div class="wa-preview-container">
                            <div class="wa-preview-thumbnail">
                                ${data.image 
                                    ? `<img src="${data.image}" style="width:100%; height:100%; object-fit:cover;" />`
                                    : `<svg class="wa-link-icon" xmlns="http://www.w3.org/2000/svg" height="24" width="24" viewBox="0 0 24 24">
                                        <path fill="#7f7f7f" d="M3.9,12A5.1,5.1,0,0,1,9,6.9h3V8.4H9A3.6,3.6,0,0,0,5.4,12,3.6,3.6,0,0,0,9,15.6h3V17.1H9A5.1,5.1,0,0,1,3.9,12ZM9.75,13.5h4.5v-3h-4.5Zm7.35-6H15V8.4h2.1a3.6,3.6,0,0,1,0,7.2H15v1.5h2.1a5.1,5.1,0,0,0,0-10.2Z"/>
                                    </svg>`
                                }
                            </div>
                            <div class="wa-preview-details">
                                <div class="wa-preview-title">${data.title}</div>
                                <div class="wa-preview-description">${data.description}</div>
                                <div class="wa-preview-domain">${new URL(data.url).hostname}</div>
                            </div>
                        </div>
                    </div>`;



                // Ensure the preview is not duplicated
                previewElement.html(previewHTML);
                previewElement.removeClass("link-preview-pending").data("processed", true);

                $(".chat-bubble").each(function () {
                    var linkPreviews = $(this).find(".link-preview");
                    if (linkPreviews.length > 1) {
                        linkPreviews.not(":first").remove(); // Keep the first one, remove the rest
                    }
                });
            },
            error: function (xhr, status, error) {
                console.error("Error fetching link preview:", error);
            }
        });
    });
}