<?php 
/**
 * Template Name: ChatRoom
 */
?>
<?php get_header()?>

<body>
    <div class="product-section">
        <div class="container">

            <!-- <div class="text-center">
                <img src="https://i.giphy.com/media/v1.Y2lkPTc5MGI3NjExam4xeXRiNGg1cm9zM3J0MWkxanB2eDY2eHZvM2dsd2Y1MTZ2emtpeCZlcD12MV9pbnRlcm5hbF9naWZfYnlfaWQmY3Q9Zw/RfEbMBTPQ7MOY/giphy.gif" class="img-fluid rounded" alt="I will be back!">
            </div> -->
            
            <form id="form" method="post" class="mx-auto p-2 grid gap-3 row gy-1" style="width: 450px;margin-top: 54px;">
                <div id="yourText"></div>
                <textarea name="textarea" id="textarea">

                </textarea>
                <button type="submit" id="submit"></button>
            </form>
        </div>
    </div>
</body>
<?php get_footer()?>
<script>
$(()=>{
    e.preventDefault();
    
    var textarea = $('#textarea').val();
    
    var youDiv = $('#yourText');

    $('button').on('submit',(e)=>{
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: ajaxurl,
            data: {
                textarea: textarea,
                action: 'send_chat_message'
            },
            success:(response)=>{
                if(response.success){
                    youDiv.append(success.message);
                }else{
                    youDiv.append(response.data.message);
                }
            }
        })
    })
});
</script>

