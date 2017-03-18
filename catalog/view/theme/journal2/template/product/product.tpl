<?php echo $header; ?>
<div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <span itemscope itemtype="http://data-vocabulary.org/Breadcrumb"><?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>" itemprop="url"><span itemprop="title"><?php echo $breadcrumb['text']; ?></span></a></span>
    <?php } ?>
</div>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content" itemscope itemtype="http://schema.org/Product">
<?php if ($this->journal2->settings->get('product_page_title_position', 'top') === 'top'): ?>
<h1 class="heading-title" itemprop="name"><?php echo $heading_title; ?></h1>
<?php endif; ?>

<?php echo $content_top; ?>
  <div class="product-info <?php echo $this->journal2->settings->get('split_ratio'); ?>" data-respond="start: 620px; end: 630px; interval: 10px;">
    <meta itemprop="url" content="<?php echo $breadcrumb['href']; ?>" />
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
          <a href="<?php echo $popup; ?>" title="<?php echo $heading_title; ?>"><img src="<?php echo $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" id="image" data-largeimg="<?php echo $popup; ?>" itemprop="image"  /></a>
      </div>
      <?php if($this->journal2->settings->get('product_page_gallery')): ?>
          <div class="gallery-text"><span><?php echo $this->journal2->settings->get('product_page_gallery_text'); ?></span></div>
      <?php endif; ?>

      <?php } ?>
      <?php if ($images) { ?>
      <div id="product-gallery" class="image-additional <?php echo $this->journal2->settings->get('product_page_gallery_carousel') ? 'journal-carousel' : 'image-additional-grid'; ?>">
        <?php if ($thumb) { ?>
        <a href="<?php echo isset($popup_fixed) ? $popup_fixed : $popup; ?>" title="<?php echo $heading_title; ?>"><img src="<?php echo isset($thumb_fixed) ? $thumb_fixed : $thumb; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" /></a>
        <?php } ?>
        <?php foreach ($images as $image) { ?>
        <a href="<?php echo $image['popup']; ?>" title="<?php echo $heading_title; ?>"><img src="<?php echo $image['thumb']; ?>" title="<?php echo $heading_title; ?>" alt="<?php echo $heading_title; ?>" itemprop="image" /></a>
        <?php } ?>
      </div>
        <?php if ($this->journal2->settings->get('product_page_gallery_carousel')): ?>
        <script>
            (function () {
                var opts = {
                    itemsCustom:[
                        [0, parseInt('<?php echo $this->journal2->settings->get('product_page_additional_width', 5) ?>', 10)],
                        [470, parseInt('<?php echo $this->journal2->settings->get('product_page_additional_width', 5) ?>', 10)],
                        [760, parseInt('<?php echo $this->journal2->settings->get('product_page_additional_width', 5) ?>', 10)],
                        [980, parseInt('<?php echo $this->journal2->settings->get('product_page_additional_width', 5) ?>', 10)],
                        [1100, parseInt('<?php echo $this->journal2->settings->get('product_page_additional_width', 5) ?>', 10)]
                    ],
                    navigation:true,
                    scrollPerPage:true,
                    navigationText : false,
                    stopOnHover: true,
                    cssAnimation: false,
                    paginationSpeed: <?php echo (int)$this->journal2->settings->get('product_page_gallery_carousel_transition_speed', 400) ?>,
                    margin:parseInt('<?php echo $this->journal2->settings->get('product_page_additional_spacing', 12) ?>', 10)
                };
                <?php if (!$this->journal2->settings->get('product_page_gallery_carousel_autoplay')): ?>
                opts.autoPlay = false;
                <?php else: ?>
                opts.autoPlay = parseInt('<?php echo $this->journal2->settings->get('product_page_gallery_carousel_transition_delay', 1000); ?>', 10);
                <?php endif; ?>
                <?php if ($this->journal2->settings->get('product_page_gallery_carousel_pause_on_hover')): ?>
                opts.stopOnHover = true;
                <?php endif; ?>
                <?php if (!$this->journal2->settings->get('product_page_gallery_carousel_touch_drag')): ?>
                opts.touchDrag = false;
                <?php endif; ?>
                jQuery("#product-gallery").owlCarousel(opts);
                <?php if ($this->journal2->settings->get('product_page_gallery_carousel_arrows') == 'hover' || $this->journal2->settings->get('product_page_gallery_carousel_arrows') == 'always'): ?>
                $('#product-gallery .owl-buttons').addClass('side-buttons');
                <?php endif; ?>
            })();
        </script>
        <?php endif; ?>
      <?php } ?>
      <?php foreach ($this->journal2->settings->get('additional_product_description_image', array()) as $tab): ?>
      <div class="journal-custom-tab">
          <?php if ($tab['has_icon']): ?>
          <div class="block-icon block-icon-left" style="<?php echo $tab['icon_css']; ?>"><?php echo $tab['icon']; ?></div>
          <?php endif; ?>
          <?php if ($tab['name']): ?>
          <h3><?php echo $tab['name']; ?></h3>
          <?php endif; ?>
          <?php echo $tab['content']; ?>
      </div>
      <?php endforeach; ?>
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
        <?php if ($this->journal2->settings->get('share_buttons_status') && (!Journal2Cache::$mobile_detect->isMobile() || (Journal2Cache::$mobile_detect->isMobile() && !$this->journal2->settings->get('share_buttons_disable_on_mobile', 1))) && $this->journal2->settings->get('share_buttons_position') === 'left' && count($this->journal2->settings->get('config_share_buttons', array()))): ?>
        <div class="social share-this <?php echo $this->journal2->settings->get('share_buttons_disable_on_mobile', 1) ? 'hide-on-mobile' : ''; ?>">
            <div class="social-loaded">
                <script type="text/javascript">var switchTo5x=true;</script>
                <script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
                <script type="text/javascript">stLight.options({publisher: "<?php echo $this->journal2->settings->get('share_buttons_account_key'); ?>", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
                <?php foreach ($this->journal2->settings->get('config_share_buttons', array()) as $item): ?>
                <span class="<?php echo $item['class'] . $this->journal2->settings->get('share_buttons_style'); ?>" displayText="<?php echo $this->journal2->settings->get('share_buttons_style') ? $item['name'] : ''; ?>"></span>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>
    </div>
    <?php } ?>
    <div class="right">
    <?php if ($this->journal2->settings->get('product_page_title_position', 'top') === 'right'): ?>
    <h1 class="heading-title" itemprop="name"><?php echo $heading_title; ?></h1>
    <?php endif; ?>
    <div class="product-options">
    <?php foreach ($this->journal2->settings->get('additional_product_description_top', array()) as $tab): ?>
    <div class="journal-custom-tab">
        <?php if ($tab['has_icon']): ?>
        <div class="block-icon block-icon-left" style="<?php echo $tab['icon_css']; ?>"><?php echo $tab['icon']; ?></div>
        <?php endif; ?>
        <?php if ($tab['name']): ?>
        <h3><?php echo $tab['name']; ?></h3>
        <?php endif; ?>
        <?php echo $tab['content']; ?>
    </div>
    <?php endforeach; ?>
      <div class="description">
        <?php if($this->journal2->settings->get('product_views')): ?>
        <span class="product-views-count"><?php echo $this->journal2->settings->get('product_page_options_views_text'); ?>: <?php echo $this->journal2->settings->get('product_views'); ?></span>
        <?php endif; ?>
        <?php if($this->journal2->settings->get('manufacturer_image') == 'on'): ?>
        <span class="brand-logo">
            <a href="<?php echo $manufacturers; ?>" class="brand-image">
                <img src="<?php echo $manufacturer_image; ?>" width="<?php echo $manufacturer_image_width; ?>" height="<?php echo $manufacturer_image_height; ?>" alt="<?php echo $manufacturer; ?>" />
            </a>
            <?php if(isset($manufacturer_image_name) && $manufacturer_image_name): ?>
            <a href="<?php echo $manufacturers; ?>" class="brand-logo-text">
                <?php echo $manufacturer_image_name; ?>
            </a>
            <?php endif; ?>
        </span>
        <?php else: ?>
        <?php if ($manufacturer) { ?>
        <span class="p-brand"><?php echo $text_manufacturer; ?></span> <a href="<?php echo $manufacturers; ?>" itemprop="manufacturer"><?php echo $manufacturer; ?></a><br />
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
    <?php if (isset($date_end) && $date_end && $this->journal2->settings->get('show_countdown_product_page', 'on') == 'on'): ?>
      <div class="countdown-wrapper"><div class="expire-text"><?php echo $this->journal2->settings->get('countdown_product_page_title'); ?></div><div class="countdown"></div></div>
      <script>Journal.countdown($('.right .countdown'), '<?php echo $date_end; ?>');</script>
      <?php endif; ?>
      <?php if ($price) { ?>
      <div class="price" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
        <meta itemprop="priceCurrency" content="<?php echo $this->journal2->settings->get('product_price_currency'); ?>" />
        <?php if ($this->journal2->settings->get('product_in_stock') === 'yes'): ?>
        <link itemprop="availability"  href="http://schema.org/InStock" />
        <?php endif; ?>
        <?php if (!$special) { ?>
        <span class="product-price" itemprop="price"><?php echo $price; ?></span>
        <?php } else { ?>
        <span class="price-old"><?php echo $price; ?></span> <span class="price-new" itemprop="price"><?php echo $special; ?></span>
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
      <?php if ($options) { ?>
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
      <script>Journal.enableSelectOptionAsButtonsList();</script>
      <?php } ?>
      <div class="cart <?php echo isset($labels) && is_array($labels) && isset($labels['outofstock']) ? 'outofstock' : ''; ?>">
        <?php if($this->journal2->settings->get('hide_add_to_cart_button')): ?>
        <?php foreach ($this->journal2->settings->get('additional_product_enquiry', array()) as $tab): ?>
        <div><?php echo $tab['content']; ?></div>
        <?php endforeach; ?>
        <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
        <?php else: ?>
        <div><span class="qty"><span class="text-qty"><?php echo $text_qty; ?></span>
          <input type="text" name="quantity" size="2" value="<?php echo $minimum; ?>" data-min-value="<?php echo $minimum; ?>" autocomplete="off" /></span>
          <input type="hidden" name="product_id" value="<?php echo $product_id; ?>" />
            <a id="button-cart" class="button"><span class="button-cart-text"><?php echo $button_cart; ?></span></a>
          <script>if ($('.product-info .image .label-outofstock').length) { $("#button-cart").addClass('button-disable').attr('disabled', 'disabled'); }</script>
        </div>
          <script>
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
        <?php if ($minimum > 1) { ?>
        <div class="minimum"><?php echo $text_minimum; ?></div>
        <?php } ?>
        <?php endif; ?>
      </div>
      <div class="wishlist-compare">
          <span class="links">
              <a onclick="addToWishList('<?php echo $product_id; ?>');"><?php echo $button_wishlist; ?></a>
              <a onclick="addToCompare('<?php echo $product_id; ?>');"><?php echo $button_compare; ?></a>
          </span>
      </div>
      <?php if ($review_status) { ?>
      <div class="review" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
        <meta itemprop="ratingValue" content="<?php echo $rating; ?>" />
        <meta itemprop="reviewCount" content="<?php echo $this->journal2->settings->get('product_num_reviews'); ?>" />
        <meta itemprop="bestRating" content="5" />
        <meta itemprop="worstRating" content="1" />
        <div><img width="83" height="15" src="catalog/view/theme/default/image/stars-<?php echo $rating; ?>.png" alt="<?php echo $reviews; ?>" />&nbsp;&nbsp;<a onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo $reviews; ?></a>&nbsp;&nbsp;&bull;&nbsp;&nbsp;<a onclick="$('a[href=\'#tab-review\']').trigger('click');"><?php echo $text_write; ?></a></div>
        <div class="share"><!-- AddThis Button BEGIN -->
          <!--<div class="addthis_default_style"><a class="addthis_button_compact"><?php echo $text_share; ?></a> <a class="addthis_button_email"></a><a class="addthis_button_print"></a> <a class="addthis_button_facebook"></a> <a class="addthis_button_twitter"></a></div>-->
          <!--<script type="text/javascript" src="//s7.addthis.com/js/250/addthis_widget.js"></script>-->
          <!-- AddThis Button END -->
        </div>
      </div>
      <?php } ?>
      <?php if ($this->journal2->settings->get('share_buttons_status') && (!Journal2Cache::$mobile_detect->isMobile() || (Journal2Cache::$mobile_detect->isMobile() && !$this->journal2->settings->get('share_buttons_disable_on_mobile', 1))) && $this->journal2->settings->get('share_buttons_position') === 'right' && count($this->journal2->settings->get('config_share_buttons', array()))): ?>
      <div class="social share-this <?php echo $this->journal2->settings->get('share_buttons_disable_on_mobile', 1) ? 'hide-on-mobile' : ''; ?>">
          <div class="social-loaded">
              <script type="text/javascript">var switchTo5x=true;</script>
              <script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
              <script type="text/javascript">stLight.options({publisher: "<?php echo $this->journal2->settings->get('share_buttons_account_key'); ?>", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
              <?php foreach ($this->journal2->settings->get('config_share_buttons', array()) as $item): ?>
              <span class="<?php echo $item['class'] . $this->journal2->settings->get('share_buttons_style'); ?>" displayText="<?php echo $this->journal2->settings->get('share_buttons_style') ? $item['name'] : ''; ?>"></span>
              <?php endforeach; ?>
          </div>
      </div>
      <?php endif; ?>
      <?php foreach ($this->journal2->settings->get('additional_product_description_bottom', array()) as $tab): ?>
      <div class="journal-custom-tab">
          <?php if ($tab['has_icon']): ?>
          <div class="block-icon block-icon-left" style="<?php echo $tab['icon_css']; ?>"><?php echo $tab['icon']; ?></div>
          <?php endif; ?>
          <?php if ($tab['name']): ?>
          <h3><?php echo $tab['name']; ?></h3>
          <?php endif; ?>
          <?php echo $tab['content']; ?>
      </div>
      <?php endforeach; ?>
  </div>
  </div>
  </div>
<?php if ($this->journal2->settings->get('share_buttons_status') && (!Journal2Cache::$mobile_detect->isMobile() || (Journal2Cache::$mobile_detect->isMobile() && !$this->journal2->settings->get('share_buttons_disable_on_mobile', 1))) && $this->journal2->settings->get('share_buttons_position') === 'bottom' && count($this->journal2->settings->get('config_share_buttons', array()))): ?>
<div class="social share-this <?php echo $this->journal2->settings->get('share_buttons_disable_on_mobile', 1) ? 'hide-on-mobile' : ''; ?>">
        <div class="social-loaded">
            <script type="text/javascript">var switchTo5x=true;</script>
            <script type="text/javascript" src="https://ws.sharethis.com/button/buttons.js"></script>
            <script type="text/javascript">stLight.options({publisher: "<?php echo $this->journal2->settings->get('share_buttons_account_key'); ?>", doNotHash: false, doNotCopy: false, hashAddressBar: false});</script>
            <?php foreach ($this->journal2->settings->get('config_share_buttons', array()) as $item): ?>
            <span class="<?php echo $item['class'] . $this->journal2->settings->get('share_buttons_style'); ?>" displayText="<?php echo $this->journal2->settings->get('share_buttons_style') ? $item['name'] : ''; ?>"></span>
            <?php endforeach; ?>
       </div>
    </div>
    <?php endif; ?>

  <div id="tabs" class="htabs">
    <?php if (!$this->journal2->settings->get('hide_product_description')) { ?>
    <a href="#tab-description"><?php echo $tab_description; ?></a>
    <?php } ?>
    <?php if ($attribute_groups) { ?>
    <a href="#tab-attribute"><?php echo $tab_attribute; ?></a>
    <?php } ?>
    <?php if ($review_status) { ?>
    <a href="#tab-review"><?php echo $tab_review; ?></a>
    <?php } ?>
    <?php if ($products) { ?>
    <a href="#tab-related" class="tab-related"><?php echo $tab_related; ?> (<?php echo count($products); ?>)</a>
    <?php } ?>
    <?php $index = 0; foreach ($this->journal2->settings->get('additional_product_tabs', array()) as $tab): $index++; ?>
    <a href="#additional-product-tab-<?php echo $index; ?>"><?php echo $tab['name']; ?></a>
    <?php endforeach; ?>
  </div>
  <?php $index = 0; foreach ($this->journal2->settings->get('additional_product_tabs', array()) as $tab): $index++; ?>
  <div id="additional-product-tab-<?php echo $index; ?>" class="tab-content journal-custom-tab"><?php echo $tab['content']; ?></div>
  <?php endforeach; ?>
  <?php if (!$this->journal2->settings->get('hide_product_description')) { ?>
  <div id="tab-description" class="tab-content" itemprop="description"><?php echo $description; ?></div>
  <?php } ?>
  <?php if ($attribute_groups) { ?>
  <div id="tab-attribute" class="tab-content">
    <table class="attribute">
      <?php foreach ($attribute_groups as $attribute_group) { ?>
      <thead>
        <tr>
          <td colspan="2"><?php echo $attribute_group['name']; ?></td>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($attribute_group['attribute'] as $attribute) { ?>
        <tr>
          <td><?php echo $attribute['name']; ?></td>
          <td><?php echo $attribute['text']; ?></td>
        </tr>
        <?php } ?>
      </tbody>
      <?php } ?>
    </table>
  </div>
  <?php } ?>
  <?php if ($review_status) { ?>
  <div id="tab-review" class="tab-content">
    <div id="review"><?php echo $this->journal2->settings->get('product_reviews'); ?></div>
    <h2 id="review-title"><?php echo $text_write; ?></h2>
    <b><?php echo $entry_name; ?></b><br />
    <input type="text" name="name" value="" />
    <br />
    <br />
    <b><?php echo $entry_review; ?></b>
    <textarea name="text" cols="40" rows="8"></textarea>
    <span><?php echo $text_note; ?></span><br />
    <br />
    <b><?php echo $entry_rating; ?></b> <span><?php echo $entry_bad; ?></span>&nbsp;
    <input type="radio" name="rating" value="1" />
    &nbsp;
    <input type="radio" name="rating" value="2" />
    &nbsp;
    <input type="radio" name="rating" value="3" />
    &nbsp;
    <input type="radio" name="rating" value="4" />
    &nbsp;
    <input type="radio" name="rating" value="5" />
    &nbsp;<span><?php echo $entry_good; ?></span><br />
    <br />
    <b><?php echo $entry_captcha; ?></b><br />
    <input type="text" name="captcha" value="" />
    <br />
    <img src="index.php?route=product/product/captcha" alt="" id="captcha" /><br />
    <br />
    <div class="buttons">
      <div class="right"><a id="button-review" class="button"><?php echo $button_continue; ?></a></div>
    </div>
  </div>
  <?php } ?>
  <?php if ($tags) { ?>
  <div class="tags"><b><?php echo $text_tags; ?></b>
    <?php for ($i = 0; $i < count($tags); $i++) { ?>
    <?php if ($i < (count($tags) - 1)) { ?>
    <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>,
    <?php } else { ?>
    <a href="<?php echo $tags[$i]['href']; ?>"><?php echo $tags[$i]['tag']; ?></a>
    <?php } ?>
    <?php } ?>
  </div>
  <?php } ?>
<?php if ($products && $this->journal2->settings->get('related_products_status')) { ?>
<div class="box related-products <?php echo $this->journal2->settings->get('related_products_carousel') ? 'journal-carousel' : ''; ?>">
   <div>
    <div class="box-heading"><?php echo $tab_related; ?></div>
    <div class="box-product">
        <?php foreach ($products as $product) { ?>
        <div class="product-grid-item <?php echo isset($product['labels']) && is_array($product['labels']) && isset($product['labels']['outofstock']) ? 'outofstock' : ''; ?>  <?php echo $this->journal2->settings->get('related_products_grid_classes'); ?> display-<?php echo $this->journal2->settings->get('product_grid_wishlist_icon_display'); ?> <?php echo $this->journal2->settings->get('product_grid_button_block_button'); ?>">
            <div class="product-wrapper">
                <?php if ($product['thumb']) { ?>
                <div class="image">
                    <a href="<?php echo $product['href']; ?>" <?php if(isset($product['thumb2']) && $product['thumb2']): ?> class="has-second-image" style="background: url('<?php echo $product['thumb2']; ?>') no-repeat;" <?php endif; ?>>
                        <img class="first-image" src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" />
                    </a>
                    <?php if (isset($product['labels']) && is_array($product['labels'])): ?>
                    <?php foreach ($product['labels'] as $label => $name): ?>
                    <?php if ($label === 'outofstock'): ?>
                    <img class="outofstock" <?php echo Journal2Utils::getRibbonSize($this->journal2->settings->get('out_of_stock_ribbon_size')); ?> style="position: absolute; top: 0; left: 0" src="<?php echo Journal2Utils::generateRibbon($name, $this->journal2->settings->get('out_of_stock_ribbon_size'), $this->journal2->settings->get('out_of_stock_font_color'), $this->journal2->settings->get('out_of_stock_bg')); ?>" alt="" />
                    <?php else: ?>
                    <span class="label-<?php echo $label; ?>"><b><?php echo $name; ?></b></span>
                    <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if($this->journal2->settings->get('product_grid_wishlist_icon_position') === 'image' && $this->journal2->settings->get('product_grid_wishlist_icon_display', '') === 'icon'): ?>
                    <div class="wishlist"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');" class="hint--top" data-hint="<?php echo $button_wishlist; ?>"><i class="wishlist-icon"></i><span class="button-wishlist-text"><?php echo $button_wishlist;?></span></a></div>
                    <div class="compare"><a onclick="addToCompare('<?php echo $product['product_id']; ?>');" class="hint--top" data-hint="<?php echo $button_compare; ?>"><i class="compare-icon"></i><span class="button-compare-text"><?php echo $button_compare;?></span></a></div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
                <?php } else { ?>
                <div class="image">
                    <a href="<?php echo $product['href']; ?>" <?php if(isset($product['thumb2']) && $product['thumb2']): ?> class="has-second-image" style="background: url('<?php echo $product['thumb2']; ?>') no-repeat;" <?php endif; ?>>
                    <img class="first-image" src="<?php echo $this->journal2->settings->get('product_no_image'); ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" />
                    </a>
                    <?php if (isset($product['labels']) && is_array($product['labels'])): ?>
                    <?php foreach ($product['labels'] as $label => $name): ?>
                    <?php if ($label === 'outofstock'): ?>
                    <img class="outofstock" <?php echo Journal2Utils::getRibbonSize($this->journal2->settings->get('out_of_stock_ribbon_size')); ?> style="position: absolute; top: 0; left: 0" src="<?php echo Journal2Utils::generateRibbon($name, $this->journal2->settings->get('out_of_stock_ribbon_size'), $this->journal2->settings->get('out_of_stock_font_color'), $this->journal2->settings->get('out_of_stock_bg')); ?>" alt="" />
                    <?php else: ?>
                    <span class="label-<?php echo $label; ?>"><b><?php echo $name; ?></b></span>
                    <?php endif; ?>
                    <?php endforeach; ?>
                    <?php if($this->journal2->settings->get('product_grid_wishlist_icon_position') === 'image' && $this->journal2->settings->get('product_grid_wishlist_icon_display', '') === 'icon'): ?>
                    <div class="wishlist"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');" class="hint--top" data-hint="<?php echo $button_wishlist; ?>"><i class="wishlist-icon"></i><span class="button-wishlist-text"><?php echo $button_wishlist;?></span></a></div>
                    <div class="compare"><a onclick="addToCompare('<?php echo $product['product_id']; ?>');" class="hint--top" data-hint="<?php echo $button_compare; ?>"><i class="compare-icon"></i><span class="button-compare-text"><?php echo $button_compare;?></span></a></div>
                    <?php endif; ?>
                    <?php endif; ?>
                </div>
                <?php } ?>
                <div class="product-details">
                <div class="name"><a href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a></div>
                <?php if ($product['price']) { ?>
                <div class="price">
                    <?php if (!$product['special']) { ?>
                    <?php echo $product['price']; ?>
                    <?php } else { ?>
                    <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new" <?php echo isset($product['date_end']) && $product['date_end'] ? "data-end-date='{$product['date_end']}'" : ""; ?>><?php echo $product['special']; ?></span>
                    <?php } ?>
                </div>
                <?php } ?>
                <?php if ($product['rating']) { ?>
                <div class="rating"><img width="83" height="15" src="<?php echo Journal2Utils::staticAsset("catalog/view/theme/default/image/stars-{$product['rating']}.png"); ?>" alt="<?php echo $product['reviews']; ?>" /></div>
                <?php } ?>
                <hr>
                <?php if (Journal2Utils::isEnquiryProduct($this, $product['product_id'])): ?>
                <div class="cart enquiry-button">
                    <a href="javascript:Journal.openPopup('<?php echo $this->journal2->settings->get('enquiry_popup_code'); ?>', '<?php echo $product['product_id']; ?>');" data-clk="addToCart('<?php echo $product['product_id']; ?>');" class="button hint--top" data-hint="<?php echo $this->journal2->settings->get('enquiry_button_text'); ?>"><?php echo $this->journal2->settings->get('enquiry_button_icon') . '<span class="button-cart-text">' . $this->journal2->settings->get('enquiry_button_text') . '</span>'; ?></a>
                </div>
                <?php else: ?>
                <div class="cart <?php echo isset($product['labels']) && is_array($product['labels']) && isset($product['labels']['outofstock']) ? 'outofstock' : ''; ?>">
                    <a onclick="addToCart('<?php echo $product['product_id']; ?>');" class="button hint--top" data-hint="<?php echo $button_cart; ?>"><i class="button-left-icon"></i><span class="button-cart-text"><?php echo $button_cart; ?></span><i class="button-right-icon"></i></a>
                </div>
                <?php endif; ?>
                <div class="wishlist"><a onclick="addToWishList('<?php echo $product['product_id']; ?>');" class="hint--top" data-hint="<?php echo $button_wishlist; ?>"><i class="wishlist-icon"></i><span class="button-wishlist-text"><?php echo $button_wishlist;?></span></a></div>
                <div class="compare"><a onclick="addToCompare('<?php echo $product['product_id']; ?>');" class="hint--top" data-hint="<?php echo $button_compare; ?>"><i class="compare-icon"></i><span class="button-compare-text"><?php echo $button_compare;?></span></a></div>
                </div>
            </div>
        </div>
        <?php } ?>
    </div>
  </div>
</div>
<?php /* enable countdown */ ?>
<?php if ($this->journal2->settings->get('show_countdown', 'never') !== 'never'): ?>
<script>
$('.related-products .product-grid-item > div').each(function () {
    var $new = $(this).find('.price-new');
    if ($new.length && $new.attr('data-end-date')) {
        $(this).find('.image').append('<div class="countdown"></div>');
    }
    Journal.countdown($(this).find('.countdown'), $new.attr('data-end-date'));
});
</script>
<?php endif; ?>
<?php if ($this->journal2->settings->get('related_products_carousel')): ?>
<?php
        $grid = Journal2Utils::getItemGrid($this->journal2->settings->get('related_products_items_per_row'), $this->journal2->settings->get('site_width', 1024), $this->journal2->settings->get('config_columns_count'));
        $grid = array(
            array(0, (int)$grid['xs']),
            array(470, (int)$grid['sm']),
            array(760, (int)$grid['md']),
            array(980, (int)$grid['lg']),
            array(1100, (int)$grid['xl'])
        );
?>
<script>
    (function () {
        var opts = {
            itemsCustom: $.parseJSON('<?php echo json_encode($grid); ?>'),
            navigation:true,
            scrollPerPage:true,
            navigationText : false,
            paginationSpeed:parseInt('<?php echo $this->journal2->settings->get('related_products_carousel_transition_speed', 400); ?>', 10),
            margin:15
        }
        <?php if (!$this->journal2->settings->get('related_products_carousel_autoplay')): ?>
        opts.autoPlay = false;
        <?php else: ?>
        opts.autoPlay = parseInt('<?php echo $this->journal2->settings->get('related_products_carousel_transition_delay', 1000); ?>', 10);
        <?php endif; ?>
        <?php if ($this->journal2->settings->get('related_products_carousel_pause_on_hover')): ?>
        opts.stopOnHover = true;
        <?php endif; ?>
        <?php if (!$this->journal2->settings->get('related_products_carousel_touch_drag')): ?>
        opts.touchDrag = false;
        <?php endif; ?>
        jQuery(".related-products .box-product").owlCarousel(opts);
        <?php if ($this->journal2->settings->get('related_products_carousel_arrows') === 'side'): ?>
        $('.related-products .box-product .owl-buttons').addClass('side-buttons');
        <?php endif; ?>

        <?php if ($this->journal2->settings->get('related_products_carousel_arrows') === 'none'): ?>
        $('.related-products .box-product .owl-buttons').hide();
        <?php endif; ?>

        <?php if (!$this->journal2->settings->get('related_products_carousel_bullets')): ?>
        $('.related-products .box-product .owl-pagination').hide();
        <?php endif; ?>
    })();
</script>
<?php endif; ?>
<script>

</script>
<?php } ?>
  <?php echo $content_bottom; ?>
</div>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.colorbox').colorbox({
		overlayClose: true,
		opacity: 0.5,
		rel: "colorbox"
	});
});
//--></script>
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
                if (!Journal.showNotification(json['success'], json['image'], true)) {
				    $('#notification').html('<div class="success" style="display: none;">' + json['success'] + '<img src="catalog/view/theme/default/image/close.png" alt="" class="close" /></div>');
                }

				$('.success').fadeIn('slow');

				$('#cart-total').html(json['total']);

          if (Journal.scrollToTop) {
              $('html, body').animate({ scrollTop: 0 }, 'slow');
          }

                if (json['redirect']) {
                    location = json['redirect'];
                }
			}
		}
	});
});
//--></script>
<?php if ($options) { ?>
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
<script type="text/javascript"><!--
$('#review .pagination a').live('click', function() {
	$('#review').fadeOut('slow');

	$('#review').load(this.href);

	$('#review').fadeIn('slow');

	return false;
});

$('#review').load('index.php?route=product/product/review&product_id=<?php echo $product_id; ?>');

$('#button-review').bind('click', function() {
	$.ajax({
		url: 'index.php?route=product/product/write&product_id=<?php echo $product_id; ?>',
		type: 'post',
		dataType: 'json',
		data: 'name=' + encodeURIComponent($('input[name=\'name\']').val()) + '&text=' + encodeURIComponent($('textarea[name=\'text\']').val()) + '&rating=' + encodeURIComponent($('input[name=\'rating\']:checked').val() ? $('input[name=\'rating\']:checked').val() : '') + '&captcha=' + encodeURIComponent($('input[name=\'captcha\']').val()),
		beforeSend: function() {
			$('.success, .warning').remove();
			$('#button-review').attr('disabled', true);
			$('#review-title').after('<div class="attention"><img src="catalog/view/theme/default/image/loading.gif" alt="" /> <?php echo $text_wait; ?></div>');
		},
		complete: function() {
			$('#button-review').attr('disabled', false);
			$('.attention').remove();
		},
		success: function(data) {
			if (data['error']) {
				$('#review-title').after('<div class="warning">' + data['error'] + '</div>');
			}

			if (data['success']) {
				$('#review-title').after('<div class="success">' + data['success'] + '</div>');

				$('input[name=\'name\']').val('');
				$('textarea[name=\'text\']').val('');
				$('input[name=\'rating\']:checked').attr('checked', '');
				$('input[name=\'captcha\']').val('');
			}
		}
	});
});
//--></script>
<script type="text/javascript"><!--
$('#tabs a').tabs();
//--></script>
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
<?php echo $footer; ?>