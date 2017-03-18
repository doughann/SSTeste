<html class="product-page quickview <?php echo $this->journal2->html_classes->getAll(); ?>" style="overflow-y: auto">
<head>
<title><?php echo $title; ?></title>
<meta name="robots" content="noindex">
<base href="<?php echo $base; ?>" />
<?php foreach ($this->journal2->google_fonts->getFonts() as $font): ?>
<link rel="stylesheet" href="<?php echo $font; ?>"/>
<?php endforeach; ?>
<?php $this->journal2->minifier->addStyle('catalog/view/javascript/jquery/ui/themes/ui-lightness/jquery-ui-1.8.16.custom.css'); ?>
<?php $this->journal2->minifier->addStyle('catalog/view/theme/journal2/css/icons.css'); ?>
<?php $this->journal2->minifier->addStyle('catalog/view/theme/journal2/css/hint.min.css'); ?>
<?php $this->journal2->minifier->addStyle('catalog/view/theme/journal2/css/journal.css'); ?>
<?php $this->journal2->minifier->addStyle('catalog/view/theme/journal2/css/module.css'); ?>
<?php $this->journal2->minifier->addStyle('catalog/view/theme/journal2/css/features.css'); ?>
<?php $this->journal2->minifier->addStyle('catalog/view/theme/journal2/css/product.css'); ?>
<?php $this->journal2->minifier->addScript('catalog/view/theme/journal2/js/journal.js', 'header'); ?>
<?php echo $this->journal2->minifier->css(); ?>
<?php if ($this->journal2->cache->getDeveloperMode() || !$this->journal2->minifier->getMinifyCss()): ?>
<link rel="stylesheet" href="index.php?route=journal2/assets/css&amp;j2v=<?php echo JOURNAL_VERSION; ?>" />
<?php endif; ?>
<?php echo $this->journal2->minifier->js('header'); ?>
</head>
<body>
<div id="container">
    <div id="content">
    <h1 class="heading-title"><?php echo $heading_title; ?></h1>
    <div class="product-info">
    <?php if ($thumb || $images) { ?>
    <div class="left">
        <?php if ($thumb) { ?>
        <div class="image">
            <?php if (isset($labels) && is_array($labels)): ?>
            <?php foreach ($labels as $label => $name): ?>
            <?php if ($label === 'outofstock'): ?>
            <img class="outofstock" <?php echo Journal2Utils::getRibbonSize($this->journal2->settings->get('out_of_stock_ribbon_size')); ?> style="z-index: 100000; position: absolute; top: 0; left: 0" src="<?php echo Journal2Utils::generateRibbon($name, $this->journal2->settings->get('out_of_stock_ribbon_size'), $this->journal2->settings->get('out_of_stock_font_color'), $this->journal2->settings->get('out_of_stock_bg')); ?>" alt="" />
            <?php else: ?>
            <span class="label-<?php echo $label; ?>"><b><?php echo $name; ?></b></span>
            <?php endif; ?>
            <?php endforeach; ?>
            <?php endif; ?>
            <a href="<?php echo $this->url->link('product/product&product_id=' . $product_id); ?>" target="_top" title="<?php echo $heading_title; ?>"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" data-largeimg="<?php echo $popup; ?>" /></a>
        </div>
        <?php } ?>
        <?php if ($images) { ?>
        <div id="product-gallery" class="image-additional <?php echo $this->journal2->settings->get('product_page_gallery_carousel') ? 'journal-carousel' : 'image-additional-grid'; ?>">
            <?php if ($thumb) { ?>
            <a href="<?php echo isset($popup_fixed) ? $popup_fixed : $popup; ?>" title="<?php echo $heading_title; ?>"><img src="<?php echo isset($thumb_fixed) ? $thumb_fixed : $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
            <?php } ?>
            <?php foreach ($images as $image) { ?>
            <a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
            <?php } ?>
        </div>
        <?php if ($this->journal2->settings->get('product_page_gallery_carousel')): ?>
        <script>
            (function () {
                jQuery("#product-gallery").owlCarousel({
                    itemsCustom:[
                [0, parseInt('<?php echo $this->journal2->settings->get('product_page_additional_width', 5) ?>', 10)],
                [470, parseInt('<?php echo $this->journal2->settings->get('product_page_additional_width', 5) ?>', 10)],
                [760, parseInt('<?php echo $this->journal2->settings->get('product_page_additional_width', 5) ?>', 10)],
                [980, parseInt('<?php echo $this->journal2->settings->get('product_page_additional_width', 5) ?>', 10)],
                [1100, parseInt('<?php echo $this->journal2->settings->get('product_page_additional_width', 5) ?>', 10)]
                ],
                autoPlay:4000,
                        navigation:true,
                        scrollPerPage:true,
                        navigationText : false,
                        stopOnHover: true,
                    paginationSpeed:400,
                        cssAnimation:false,
                        margin:parseInt('<?php echo $this->journal2->settings->get('product_page_additional_spacing', 12) ?>', 10)
            });
            <?php if ($this->journal2->settings->get('product_page_gallery_carousel_arrows') == 'hover' || $this->journal2->settings->get('product_page_gallery_carousel_arrows') == 'always'): ?>
            $('#product-gallery .owl-buttons').addClass('side-buttons');
            <?php endif; ?>
            })();
        </script>
        <?php endif; ?>
        <?php } ?>
        <div class="image-gallery" style="display: none !important;">
            <?php if ($thumb) { ?>
            <a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>" class="swipebox"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
            <?php } ?>
            <?php if ($images) { ?>
            <?php foreach ($images as $image) { ?>
            <a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>" class="swipebox"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
            <?php } ?>
            <?php } ?>
        </div>
        <?php if ($this->journal2->settings->get('quickview_description_position') == 'image'): ?>
        <div id="tab-description" class="tab-content"><?php echo $description; ?></div>
        <?php endif; ?>
    </div>
    <?php } else { ?>
    <div class="left no-images">No images available.</div>
    <?php } ?>
    <div class="right">
    <div class="product-options">
    <div class="description">
        <?php if($this->journal2->settings->get('product_views')): ?>
        <span class="product-views-count"><?php echo $this->journal2->settings->get('product_page_options_views_text'); ?>: <?php echo $this->journal2->settings->get('product_views'); ?></span>
        <?php endif; ?>
        <?php if($this->journal2->settings->get('manufacturer_image') == 'on'): ?>
        <span class="brand-logo">
            <a href="<?php echo $manufacturers; ?>" target="_top" class="brand-image">
                <img src="<?php echo $manufacturer_image; ?>" width="<?php echo $manufacturer_image_width; ?>" height="<?php echo $manufacturer_image_height; ?>" alt="<?php echo $manufacturer; ?>" />
            </a>
            <?php if(isset($manufacturer_image_name) && $manufacturer_image_name): ?>
            <a href="<?php echo $manufacturers; ?>" target="_top" class="brand-logo-text">
                <?php echo $manufacturer_image_name; ?>
            </a>
            <?php endif; ?>
        </span>
        <?php else: ?>
        <?php if ($manufacturer) { ?>
        <span><?php echo $text_manufacturer; ?></span> <a href="<?php echo $manufacturers; ?>" target="_top" itemprop="manufacturer"><?php echo $manufacturer; ?></a><br />
        <?php } ?>
        <?php endif; ?>
        <span  class="p-model"><?php echo $text_model; ?></span> <span class="p-model" itemprop="model"><?php echo $model; ?></span><br />
        <?php if ($reward) { ?>
        <span class="p-rewards"><?php echo $text_reward; ?></span> <span class="p-rewards"><?php echo $reward; ?></span><br />
        <?php } ?>
        <span class="p-stock"><?php echo $text_stock; ?></span> <span class="journal-stock <?php echo isset($stock_status) ? $stock_status : ''; ?>"><?php echo $stock; ?></span>
    </div>
    <?php if($this->journal2->settings->get('product_sold')): ?>
    <div class="product-sold-count-text"><?php echo $this->journal2->settings->get('product_sold'); ?></div>
    <?php endif; ?>
    <?php if ($price) { ?>
    <div class="price">
        <?php if (!$special) { ?>
        <span class="product-price"><?php echo $price; ?></span>
        <?php } else { ?>
        <span class="price-old"><?php echo $price; ?></span> <span class="price-new"><?php echo $special; ?></span>
        <?php } ?>
        <?php if ($tax) { ?>
        <span class="price-tax"><?php echo $text_tax; ?> <?php echo $tax; ?></span>
        <?php } ?>
        <?php if ($points) { ?>
        <span class="reward"><small><?php echo $text_points; ?> <?php echo $points; ?></small></span>
        <?php } ?>
        <?php if ($discounts) { ?>

        <div class="discount">
            <?php foreach ($discounts as $discount) { ?>
            <?php echo sprintf($text_discount, $discount['quantity'], $discount['price']); ?><br />
            <?php } ?>
        </div>
        <?php } ?>
    </div>
    <?php } ?>
    <?php if (isset($profiles)): /* v156 compatibility */ ?>
    <?php if ($profiles): ?>

    <div class="option">
        <h2><span class="required">*</span><?php echo $text_payment_profile ?></h2>
        <select name="profile_id">
            <option value=""><?php echo $text_select; ?></option>
            <?php foreach ($profiles as $profile): ?>
            <option value="<?php echo $profile['profile_id'] ?>"><?php echo $profile['name'] ?></option>
            <?php endforeach; ?>
        </select>
        <br />
        <span id="profile-description"></span>
        <br />
    </div>
    <?php endif; ?>
    <?php endif; /* end v156 compatibility */ ?>
    <?php if ($options && $this->journal2->settings->get('quickview_product_options') === '1') { ?>
    <div class="options <?php echo $this->journal2->settings->get('product_page_options_push_classes'); ?>">
        <h3><?php echo $text_option; ?></h3>
        <br />
        <?php foreach ($options as $option) { ?>
        <?php if ($option['type'] == 'select') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option option-<?php echo $option['type']; ?>">
            <?php if ($option['required']) { ?>
            <span class="required">*</span>
            <?php } ?>
            <b><?php echo $option['name']; ?>:</b><br />
            <select name="option[<?php echo $option['product_option_id']; ?>]">
                <option value=""><?php echo $text_select; ?></option>
                <?php foreach ($option['option_value'] as $option_value) { ?>
                <option value="<?php echo $option_value['product_option_value_id']; ?>" data-image="<?php echo $option_value['image']; ?>"><?php echo $option_value['name']; ?>
                    <?php if ($option_value['price']) { ?>
                    (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                    <?php } ?>
                </option>
                <?php } ?>
            </select>
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'radio') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option option-<?php echo $option['type']; ?>">
            <?php if ($option['required']) { ?>
            <span class="required">*</span>
            <?php } ?>
            <b><?php echo $option['name']; ?>:</b><br />
            <?php foreach ($option['option_value'] as $option_value) { ?>
            <input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" data-image="<?php echo $option_value['image']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
            <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                <?php if ($option_value['price']) { ?>
                (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                <?php } ?>
            </label>
            <br />
            <?php } ?>
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'checkbox') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option option-<?php echo $option['type']; ?>">
            <?php if ($option['required']) { ?>
            <span class="required">*</span>
            <?php } ?>
            <b><?php echo $option['name']; ?>:</b><br />
            <?php foreach ($option['option_value'] as $option_value) { ?>
            <input type="checkbox" name="option[<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" data-image="<?php echo $option_value['image']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" />
            <label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                <?php if ($option_value['price']) { ?>
                (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                <?php } ?>
            </label>
            <br />
            <?php } ?>
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'image') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option option-<?php echo $option['type']; ?>">
            <?php if ($option['required']) { ?>
            <span class="required">*</span>
            <?php } ?>
            <b><?php echo $option['name']; ?>:</b><br />
            <table class="option-image">
                <?php foreach ($option['option_value'] as $option_value) { ?>
                <tr>
                    <td style="width: 1px;"><input type="radio" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" id="option-value-<?php echo $option_value['product_option_value_id']; ?>" /></td>
                    <td><label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><img src="<?php echo $option_value['image']; ?>" alt="<?php echo $option_value['name'] . ($option_value['price'] ? ' ' . $option_value['price_prefix'] . $option_value['price'] : ''); ?>" /></label></td>
                    <td><label for="option-value-<?php echo $option_value['product_option_value_id']; ?>"><?php echo $option_value['name']; ?>
                        <?php if ($option_value['price']) { ?>
                        (<?php echo $option_value['price_prefix']; ?><?php echo $option_value['price']; ?>)
                        <?php } ?>
                    </label></td>
                </tr>
                <?php } ?>
            </table>
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'text') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
            <?php if ($option['required']) { ?>
            <span class="required">*</span>
            <?php } ?>
            <b><?php echo $option['name']; ?>:</b><br />
            <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" />
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'textarea') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
            <?php if ($option['required']) { ?>
            <span class="required">*</span>
            <?php } ?>
            <b><?php echo $option['name']; ?>:</b><br />
            <textarea name="option[<?php echo $option['product_option_id']; ?>]" cols="40" rows="5"><?php echo $option['option_value']; ?></textarea>
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'file') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
            <?php if ($option['required']) { ?>
            <span class="required">*</span>
            <?php } ?>
            <b><?php echo $option['name']; ?>:</b><br />
            <input type="button" value="<?php echo $button_upload; ?>" id="button-option-<?php echo $option['product_option_id']; ?>" class="button">
            <input type="hidden" name="option[<?php echo $option['product_option_id']; ?>]" value="" />
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'date') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
            <?php if ($option['required']) { ?>
            <span class="required">*</span>
            <?php } ?>
            <b><?php echo $option['name']; ?>:</b><br />
            <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="date" />
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'datetime') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
            <?php if ($option['required']) { ?>
            <span class="required">*</span>
            <?php } ?>
            <b><?php echo $option['name']; ?>:</b><br />
            <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="datetime" />
        </div>
        <br />
        <?php } ?>
        <?php if ($option['type'] == 'time') { ?>
        <div id="option-<?php echo $option['product_option_id']; ?>" class="option">
            <?php if ($option['required']) { ?>
            <span class="required">*</span>
            <?php } ?>
            <b><?php echo $option['name']; ?>:</b><br />
            <input type="text" name="option[<?php echo $option['product_option_id']; ?>]" value="<?php echo $option['option_value']; ?>" class="time" />
        </div>
        <br />
        <?php } ?>
        <?php } ?>
    </div>
    <?php } ?>
    <script>Journal.enableSelectOptionAsButtonsList();</script>
    <div class="cart <?php echo isset($labels) && is_array($labels) && isset($labels['outofstock']) ? 'outofstock' : ''; ?>">
        <div>
            <?php if(!$this->journal2->settings->get('hide_add_to_cart_button')): ?>
            <span class="qty"><span class="text-qty"><?php echo $text_qty; ?></span>
            <input type="text" name="quantity" size="2" value="<?php echo $minimum; ?>" data-min-value="<?php echo $minimum; ?>" autocomplete="off" /></span>
            <input type="hidden" name="product_id" size="2" value="<?php echo $product_id; ?>" />
            <a id="button-cart" class="button"><i class="button-left-icon"></i><span class="button-cart-text"><?php echo $button_cart; ?></span></i></a>
            <a id="more-details" class="button hint--top" data-hint="<?php echo ($this->journal2->settings->get('quickview_more_details_text')); ?>" target="_top" href="<?php echo $this->url->link('product/product&product_id=' . $product_id); ?>"><i></i></a>
            <?php else: ?>
            <a id="more-details" class="button enquiry-button" target="_top" href="<?php echo $this->url->link('product/product&product_id=' . $product_id); ?>"><?php echo ($this->journal2->settings->get('quickview_more_details_text')); ?></a>
            <?php endif; ?>
        </div>
        <?php if(!$this->journal2->settings->get('hide_add_to_cart_button')): ?>
        <script>
            if ($('.product-info .image .label-outofstock').length) { $("#button-cart").addClass('button-disable').attr('disabled', 'disabled'); }
            /* quantity buttons */
            var $input = $('.cart input[name="quantity"]');
            function up() {
                var val = parseInt($input.val(), 10) + 1 || parseInt($input.attr('data-min-value'), 10);
                $input.val(val);
            }
            function down() {
                var val = parseInt($input.val(), 10) - 1 || 0;
                var min = parseInt($input.attr('data-min-value'), 10) || 1;
                $input.val(Math.max(val, min));
            }
            $('<a href="javascript:;" class="journal-stepper">-</a>').insertBefore($input).click(down);
            $('<a href="javascript:;" class="journal-stepper">+</a>').insertAfter($input).click(up);
            $input.keydown(function (e) {
                if (e.which === 38) {
                    up();
                    return false;
                }
                if (e.which === 40) {
                    down();
                    return false;
                }
            });
        </script>
        <?php endif; ?>
        <?php if ($minimum > 1) { ?>
        <div class="minimum"><?php echo $text_minimum; ?></div>
        <?php } ?>
    </div>
    <div class="wishlist-compare">
          <span class="links">
              <a onclick="parent.addToWishList('<?php echo $product_id; ?>');"><?php echo $button_wishlist; ?></a>
              <a onclick="parent.addToCompare('<?php echo $product_id; ?>');"><?php echo $button_compare; ?></a>
          </span>
    </div>
    <?php if ($this->journal2->settings->get('quickview_description_position') == 'options'): ?>
        <div id="tab-description" class="tab-content"><?php echo $description; ?></div>
    <?php endif; ?>

    </div>
    </div>
    </div>
    <?php if ($this->journal2->settings->get('quickview_description_position') == 'bottom'): ?>
    <div id="tab-description" class="tab-content"><?php echo $description; ?></div>
    <?php endif; ?>
    </div>
</div>
<script type="text/javascript"><!--

$('select[name="profile_id"], input[name="quantity"]').change(function(){
    $.ajax({
		url: 'index.php?route=product/product/getRecurringDescription',
		type: 'post',
		data: $('input[name="product_id"], input[name="quantity"], select[name="profile_id"]'),
		dataType: 'json',
        beforeSend: function() {
            $('#profile-description').html('');
        },
		success: function(json) {
			$('.success, .warning, .attention, information, .error').remove();

			if (json['success']) {
                $('#profile-description').html(json['success']);
			}
		}
	});
});

$('#button-cart').bind('click', function() {
    if ($('.hide-cart .right .cart.outofstock').length) {
        return false;
    }
	$.ajax({
		url: 'index.php?route=checkout/cart/add',
		type: 'post',
		data: $('.product-info input[type=\'text\'], .product-info input[type=\'hidden\'], .product-info input[type=\'radio\']:checked, .product-info input[type=\'checkbox\']:checked, .product-info select, .product-info textarea'),
		dataType: 'json',
		success: function(json) {
			$('.success, .warning, .attention, information, .error').remove();

			if (json['error']) {
                <?php if ($this->journal2->settings->get('quickview_product_options') !== '1'): ?>
                top.location = '<?php echo str_replace('&amp;', '&', $this->url->link('product/product', 'product_id=' . $product_id)); ?>';
                return;
                <?php endif; ?>
				if (json['error']['option']) {
					for (i in json['error']['option']) {
						$('#option-' + i).after('<span class="error">' + json['error']['option'][i] + '</span>');
					}
				}

                if (json['error']['profile']) {
                    $('select[name="profile_id"]').after('<span class="error">' + json['error']['profile'] + '</span>');
                }
			}

			if (json['success']) {
                if (!parent.Journal.showNotification(json['success'], json['image'], true)) {
				    parent.$('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
                }

				$('.success').fadeIn('slow');

				parent.$('#cart-total').html(json['total']);

				$('html, body').animate({ scrollTop: 0 }, 'slow');

                if (json['redirect']) {
                    top.location.href = json['redirect'];
                }
			}
		}
	});
});
//--></script>
<?php if ($options && $this->journal2->settings->get('quickview_product_options') === '1') { ?>
<script type="text/javascript" src="catalog/view/javascript/jquery/ajaxupload.js"></script>
<?php foreach ($options as $option) { ?>
<?php if ($option['type'] == 'file') { ?>
<script type="text/javascript"><!--
new AjaxUpload('#button-option-<?php echo $option['product_option_id']; ?>', {
	action: 'index.php?route=product/product/upload',
	name: 'file',
	autoSubmit: true,
	responseType: 'json',
	onSubmit: function(file, extension) {
		$('#button-option-<?php echo $option['product_option_id']; ?>').after('<img src="catalog/view/theme/default/image/loading.gif" class="loading" style="padding-left: 5px;" />');
		$('#button-option-<?php echo $option['product_option_id']; ?>').attr('disabled', true);
	},
	onComplete: function(file, json) {
		$('#button-option-<?php echo $option['product_option_id']; ?>').attr('disabled', false);

		$('.error').remove();

		if (json['success']) {
			alert(json['success']);

			$('input[name=\'option[<?php echo $option['product_option_id']; ?>]\']').attr('value', json['file']);
		}

		if (json['error']) {
			$('#option-<?php echo $option['product_option_id']; ?>').after('<span class="error">' + json['error'] + '</span>');
		}

		$('.loading').remove();
	}
});
//--></script>
<?php } ?>
<?php } ?>
<?php } ?>
<script>
    Journal.productPage();
    <?php if($this->journal2->settings->get('product_page_auto_update_price', '1') === '1'): ?>
    Journal.enableProductOptions();
    Journal.updatePrice = true;
    <?php endif; ?>
    <?php if ($this->journal2->settings->get('quickview_cloud_zoom') === '1'): ?>
    Journal.enableCloudZoom('inner');
    <?php endif; ?>
    $('.image > a').live('click', function () {
        top.location.href = "<?php echo $this->url->link('product/product&product_id=' . $product_id); ?>";
        return false;
    });
</script>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
    if ($.browser.msie && $.browser.version == 6) {
        $('.date, .datetime, .time').bgIframe();
    }

    $('.date').datepicker({dateFormat: 'yy-mm-dd'});
    $('.datetime').datetimepicker({
        dateFormat: 'yy-mm-dd',
        timeFormat: 'h:m'
    });
    $('.time').timepicker({timeFormat: 'h:m'});
});
//--></script>
<script><?php echo $this->journal2->settings->get('custom_js'); ?></script>
</body>
</html>
