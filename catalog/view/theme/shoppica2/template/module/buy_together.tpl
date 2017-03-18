<style>
.buy-together{
    list-style: none;
    overflow: hidden;
    margin: 0px;
    padding: 0px;
}
.buy-together li{
    float: left;
    height: 60px;
    padding: 10px 10px 20px;
    text-align: center;
}
.buy-together li span.plus{
    line-height: 40px;
    font-size: 1.6em;
}

.buy-together li span.message{
}

.buy-together li span.discounted-price{
    color: #990000;
    font-weight: bold;
}

.buy-together li span.price{
    font-weight: bold;
    text-decoration: line-through;
}

.buy-together p{
    margin: 0px;
}
</style>

<script>
var productIds = [];
$(function(){
    $('.buy-together-cart').each(function(){
        $(this).bind('click', function() {
            discountId = $(this).attr('name').match(/\d+/)[0]
            productIds = $('#discountIds-'+discountId).val().split(',')
            $('.success, .warning, .attention, information, .error').remove();
            $("#product_info p.s_error_msg").remove();
            sendItemToCart()
        });
    })
})

function sendItemToCart()
{
    if(productIds.length>0){
        $.ajax({
            url: 'index.php?route=tb/cartCallback',
            type: 'post',
            data: {quantity:1, product_id: productIds.shift()},
            dataType: 'json',
            success: function(json) {
/*                if (json['error']) {
                    if (json['error']['option']) {
                        for (i in json['error']['option']) {
                            $('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
                        }
                    }
                }

                if (json['success']) {
                    $('#notification').append('<div class="success" style="display: none;">'+json['success']+'<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
                    $('.success').fadeIn('slow');
                    $('#cart-total').html(json['total']);
                    $('html, body').animate({ scrollTop: 0 }, 'slow');
                }*/

                if (json['error']) {
                    if (json['error']['warning']) {
                        productNotice(json['title'], json['thumb'], json['error']['warning'], 'failure');
                        $('.warning').fadeIn('slow');
                    }

                    for (i in json['error']) {
                        $('#option-' + i).append('<p class="s_error_msg">' + json['error'][i] + '</p>');
                    }
                }

                if (json['success']) {
                    productNotice(json['title'], json['thumb'], json['success'], 'success');
                    $('#cart_menu span.s_grand_total').html(json['total_sum']);
                    $('#cart_menu div.s_cart_holder').html(json['html']);
                }
                sendItemToCart()
            }
        });
    }
}
</script>

<div class="box">
    <h2 class="s_title_1"><span><?php echo $heading_title; ?></span></h2>
  <div class="box-content">

        <?php if(!function_exists('displayProduct')){
        function displayProduct($options, $product, $first = false){?>
        <?if($product):?>
        <?if(!$first):?>
        <li>
            <span class="plus">+</span>
        </li>
        <?php endif?>
        <li>
            <a href="<?php echo $product['href']; ?>" target="_blank"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" />
            <?php if($options['display']==1):?>
                <p><?php echo $product['name']?></p>
            <?php endif;?>
            </a>
        </li>
        <?php endif?>
        <?php }}?>

    <?$discountId = 0?>
    <?foreach($discounts as $discount):?>
        <input type='hidden' id="discountIds-<?=$discountId?>" value="<?=$discount['productIds']?>">
    <ul class="buy-together">
        <?php displayProduct($displayOptions,$discount['productA'], true)?>
        <?php displayProduct($displayOptions,$discount['productB'])?>
        <?php displayProduct($displayOptions,$discount['productC'])?>
        <?php displayProduct($displayOptions,$discount['productD'])?>

        <li>
            <div><span class="message"><?=$discount['message']?></span>
            <span class="price"><?=$discount['price']?></span>
            <span class="discounted-price"><?=$discount['discountedPrice']?></span></div>
            <p>
                <span><?=$lang->get('buy_together_you_save')?> <span class="discounted-price"><?=($discount['discountType']=='fixed'?$discount['discountAmount']:$discount['discount'] . '% (' . $discount['discountAmount'] . ')')?></span></span>
            </p>
            <p>
                <a id="add_to_cart" class="s_button_1 s_main_color_bgr buy-together-cart" name='id-<?=$discountId++?>'>
                    <span class="s_text s_icon_24"><span class="s_icon"></span> <?php echo $discount['button']; ?></span>
                </a>
            </p>
        </li>
        </ul>
    <?endforeach?>
  </div>
</div>
