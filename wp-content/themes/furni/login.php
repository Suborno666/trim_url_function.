<?php
/** 
 * Template Name: Login
 * */ 
?>
<?php get_header()?>
<div class="product-section">
    <div class="container">
        <!-- heading -->
        <h3 class="lead" style="display:flex;justify-content:center;margin-top: 154px;">
            <?php
                the_title();
            ?>
        </h3>
        
        <form id="form" method="post" class="mx-auto p-2 grid gap-3 row gy-1" style="width: 450px;margin-top: 54px;">
            <?php wp_nonce_field('custom_login_nonce', 'custom_login_nonce_field'); ?>
            <input type="hidden" name="action" value="custom_login">
            <div class="form-group">
                <label for="username">Type your username</label>
                <input type="text" name="username" class="form-control" id="username" placeholder="Username">
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" name="user_password" class="form-control" id="exampleInputPassword1" placeholder="Password">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            <p id="response"> </p>
        </form>
    </div>
</div>

<?php get_footer()?>
<script>
jQuery(function($) {
    $("#form").on('submit', function(e) {
        e.preventDefault();
        var formData = new FormData(this);
        formData.append("action", "custom_login");
        
        $.ajax({
            type: "POST",
            url: "<?php echo admin_url('admin-ajax.php'); ?>",
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success: function(res) {
                if(res.loggedin) {
                    $("#response").html(res.message).css('color','green');
                    window.location.href = "<?php echo home_url(); ?>";
                } else {
                    $("#response").html(res.message).css('color','red');
                }
            },
            error: function(xhr, status, error) {
                console.error(xhr.responseText);
                $("#response").html("An error occurred. Please try again.").css('color','red');
            }
        });
    });
});
</script>