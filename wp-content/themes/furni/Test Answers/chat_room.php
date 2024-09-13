<?php 
/**
 * Template Name: ChatRoom
 */
?>
<?php get_header()?>
<?php
    global $wpdb;
    $post_id = get_the_ID();
        
    $current_user = wp_get_current_user();
    $user_email = $current_user->user_email;
    
    $table_name = $wpdb->prefix . 'chat_messages';
    $results = $wpdb->get_results(
        $wpdb->prepare(
            "SELECT * FROM $table_name ORDER BY createdAt ASC"
        )
    );
?>
<body>
    <div class="product-section">
        <div class="container">
            <form id="chat-form" class="mx-auto p-2 grid gap-3 row gy-1" style="width: 450px;margin-top: 54px;">
                <div id="chat-messages" class="text-messages ">
                    <?php foreach($results as $message): ?>
                        <p class="bold <?php echo ($message->sent_by == $user_email) ? 'me-auto text-end bg-light' : 'text-start bg-light'; ?> p-2 mb-2 rounded">
                            <?php echo $message->sent_by.': '?><?php echo $message->message;?>
                        </p>
                    <?php endforeach; ?>
                </div>
                <textarea name="message" id="chat-textarea" class="form-control mb-2"></textarea>
                <button type="submit" id="chat-submit" class="btn btn-primary">Send</button>
            </form>
        </div>
    </div>
</body>

<?php get_footer()?>


<script>
jQuery(function($) {
    function fetchMessages() {
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'fetch_chat_messages'
            },
            success: function(response) {
                if (response.success) {
                    var messages = response.data.messages;
                    var chatMessages = $('#chat-messages');
                    chatMessages.empty(); 

                    messages.forEach(function(message) {
                        var alignmentClass = message.sent_by === '<?php echo $user_email; ?>' ? 'me-auto text-end bg-light' : 'text-start bg-light';
                        var timestamp = '<p class="timestamp"><em>' + new Date(message.createdAt).toLocaleTimeString()+'</em></p>';
                        chatMessages.append('<p class="bold ' + alignmentClass + ' p-2 mb-2 rounded">' + message.sent_by + ': ' + message.message + '</p>' + timestamp );
                    });

                } else {
                    console.log('Failed to fetch messages');
                }
            },
            error: function() {
                console.log('Error fetching messages');
            }
        });
    }

    setInterval(fetchMessages, 1000);

    $('#chat-form').on('submit', function(e) {
        e.preventDefault();
        
        var message = $('#chat-textarea').val().trim();
        if (!message) {
            alert('Please enter a message.');
            return;
        }

        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                action: 'send_chat_message',
                textarea: message
            },
            success: function(response) {
                if (response.success) {
                    $('#chat-textarea').val('');
                    fetchMessages(); 
                } else {
                    alert(response.data.message);
                }
            },
            error: function() {
                alert('An error occurred. Please try again.');
                console.log("it failed");
            }
        });
    });
});

</script>

