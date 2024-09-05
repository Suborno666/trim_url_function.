jQuery(document).ready(function($) {
    $('#publish_new_products').on('click', function(e) {
        e.preventDefault();
        
        $.ajax({
            url: adminAjax.ajax_url,
            type: 'POST',
            dataType: 'json', 
            data: {
                action: 'publish_new_products',
            },
            beforeSend: function() {
                $('#publish_new_products').prop('disabled', true).text('Publishing...');
            },
            success: function(response) {
                if (response.success) {
                    alert('Success: ' + response.data.message);
                    location.reload();
                } else {
                    alert('Error: ' + response.data.message);
                }
            },
            error: function(xhr, status, error) {
                console.error('AJAX Error:', status, error);
                alert('An error occurred while publishing products. Please try again.');
            },
            complete: function() {
                $('#publish_new_products').prop('disabled', false).text('Publish New Products');
            }
        });
    });
});