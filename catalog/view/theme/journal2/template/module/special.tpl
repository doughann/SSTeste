<div class="box oc-module">
  <div class="box-heading"><?php echo $heading_title; ?></div>
  <div class="box-content">
    <div class="box-product">
      <?php foreach ($products as $product) { ?>
        <div class="product-grid-item <?php echo isset($product['labels']) && is_array($product['labels']) && isset($product['labels']['outofstock']) ? 'outofstock' : ''; ?>  <?php echo $this->journal2->settings->get('product_grid_classes'); ?> display-<?php echo $this->journal2->settings->get('product_grid_wishlist_icon_display');?> <?php echo $this->journal2->settings->get('product_grid_button_block_button');?>">
            <div class="product-wrapper">
        <?php if ($product['thumb']) { ?>
                <div class="image">
                    <a href="<?php echo $product['href']; ?>" <?php if(isset($product['thumb2']) && $product['thumb2']): ?> class="has-second-image" style="background: url('<?php echo $product['thumb2']; ?>') no-repeat;" <?php endif; ?>>
                        <img class="first-image"  src="<?php echo $product['thumb']; ?>" title="<?php echo $product['name']; ?>" alt="<?php echo $product['name']; ?>" />
                    </a>
                    <?php if (isset($product['labels']) && is_array($product['labels'])): ?>
                    <?php foreach ($product['labels'] as $label => $name): ?>
                    <?php if ($label === 'outofstock'): ?>
                    <img class="outofstock" <?php echo Journal2Utils::getRibbonSize($this->journal2->settings->get('out_of_stock_ribbon_size')); ?> style="position: absolute; top: 0; left: 0" src="<?php echo Journal2Utils::generateRibbon($name, $this->journal2->settings->get('out_of_stock_ribbon_size'), $this->journal2->settings->get('out_of_stock_font_color'), $this->journal2->settings->get('out_of_stock_bg')); ?>" alt="" />
                    <?php else: ?>
                    <span class="label-<?php echo $label; ?>"><b><?php echo $name; ?></b></span>
                    <?php endif; ?>
                    <?php endforeach; ?>
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
          <span class="price-old"><?php echo $product['price']; ?></span> <span class="price-new"><?php echo $product['special']; ?></span>
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
      </div>
      </div>
      </div>
      <?php } ?>
    </div>
  </div>
  <script>Journal.itemsEqualHeight();</script>
</div>
