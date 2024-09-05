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
            <input type="hidden" name="action" value="custom_login">
            <div class="form-group">
                <label for="exampleInputEmail1">Email address</label>
                <input type="email" name="email" class="form-control" id="exampleInputEmail1" aria-describedby="emailHelp" placeholder="Enter email">
                <small id="emailHelp" class="form-text text-muted">We'll never share your email with anyone else.</small>
            </div>
            <div class="form-group">
                <label for="exampleInputPassword1">Password</label>
                <input type="password" name="password" class="form-control" id="exampleInputPassword1" placeholder="Password">
            </div>
            <button type="submit" name="submit" class="btn btn-primary">Submit</button>
            <p id="response">
                
            </p>
            <p></p>
        </form>
    </div>
</div>

<?php get_footer()?>
<script>
$(()=>{
    $("#form").on('submit',(e)=>{
        e.preventDefault();
        var formData = new FormData($('#form')[0]);
        // formData.append("action", "custom_login");
        for( var [key,value] of formData.entries()){
            console.log(key,"=>",value)
        }
        $.ajax({
            type:"POST",
            url: ajaxurl,
            data: formData,
            dataType: 'json',
            processData: false,
            contentType: false,
            success:(res)=>{
                if(res.loggedin){
                    $("#response").html(res.message);
                    $("#response").css('color','green');
                    location='http://localhost/wordpress/';
                }else{
                    $("#response").html(res.message);
                    $("#response").css('color','red');
                }
                
            }
        })
    })
})
</script>