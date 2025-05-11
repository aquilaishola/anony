let offset = 0;

// Function to fetch messages
function fetchMessages() {
    $.ajax({
        url: 'messages.php',
        type: 'GET',
        data: {
            fetch_messages: 1, 
            offset: offset
        },
        success: function (response) {
            if (response.trim() === "No messages found.") {
                // If no messages are found, hide the button and stop loading
                $('#load-more-btn').hide();
            } else {
                $('#message-container').append(response);
                offset += 50; // Increase offset only if messages are returned
            }
        }
    });
}

// Initial fetch
fetchMessages();

// Load more messages on button click
$('#load-more-btn').click(function () {
    fetchMessages();
});

// Show modal with image
$(document).on('click', '.view-image', function () {
    const imageUrl = $(this).data('image');
    if (imageUrl) {
        $('#modal-image').attr('src', imageUrl);
        $('#image-modal').fadeIn();
    }
});

// Close modal
$('#modal-close').click(function () {
    $('#image-modal').fadeOut();
});

// Share message text when social buttons are clicked
$(document).on('click', '.share-btn', function () {
    const messageId = $(this).data('id');
    const messageElement = document.getElementById(messageId);
    const messageContent = messageElement.querySelector('.message-content').innerText;

    // Create the message template
    const templateMessage = `Check out this message sent to me on Anony.devaquila.xyz 🎉 - ${encodeURIComponent(messageContent)}`;

    // WhatsApp Sharing
    if ($(this).hasClass('whatsapp')) {
        const whatsappUrl = `https://wa.me/?text=${templateMessage}`;
        window.open(whatsappUrl, '_blank');
    }

    // Facebook Sharing
    if ($(this).hasClass('facebook')) {
        const facebookUrl = `https://www.facebook.com/sharer/sharer.php?u=${encodeURIComponent(templateMessage)}`;
        window.open(facebookUrl, '_blank');
    }
});