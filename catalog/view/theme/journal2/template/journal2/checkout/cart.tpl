<div class="checkout-content checkout-cart">
    <h2 class="secondary-title"><?php echo $this->journal2->settings->get('one_page_lang_shop_cart', 'Shopping Cart'); ?></h2>
    <div class="table-responsive checkout-product">
        <table class="table table-bordered table-hover">
            <thead>
            <tr>
                <td class="text-left name" colspan="2"><?php echo $column_name; ?></td>
                <!--<td class="text-left model"><?php echo $column_model; ?></td>-->
                <td class="text-left quantity"><?php echo $column_quantity; ?></td>
                <td class="text-right price"><?php echo $column_price; ?></td>
                <td class="text-right total"><?php echo $column_total; ?></td>
            </tr>
            </thead>
            <tbody>
            <?php foreach ($products as $product) { ?>
                <tr>
                    <td class="text-center image"><?php if ($product['thumb']) { ?>
                            <a href="<?php echo $product['href']; ?>"><img src="<?php echo $product['thumb']; ?>" alt="<?php echo $product['name']; ?>" title="<?php echo $product['name']; ?>" class="img-thumbnail" /></a>
                        <?php } ?></td>
                    <td class="text-left name"><a
                            href="<?php echo $product['href']; ?>"><?php echo $product['name']; ?></a>
                        <?php foreach ($product['option'] as $option) { ?>
                            <br/>
                            &nbsp;
                            <small> - <?php echo $option['name']; ?>: <?php echo $option['value']; ?></small>
                        <?php } ?>
                        <?php if ($product['recurring']) { ?>
                            <br/>
                            <span class="label label-info"><?php echo $text_recurring_item; ?></span>
                            <small><?php echo $product['recurring']; ?></small>
                        <?php } ?></td>
                    <!--<td class="text-left model"><?php echo $product['model']; ?></td>-->
                    <td class="quantity"><input type="text" name="quantity[<?php echo $product['key']; ?>]" value="<?php echo $product['quantity']; ?>" size="1" />
                        <span class="input-group">
                            <input type="image" class="btn-update" src="catalog/view/theme/default/image/update.png" data-product-key="<?php echo $product['key']; ?>" alt="<?php echo $button_update; ?>" title="<?php echo $button_update; ?>" />
                            <input type="image" class="btn-delete" src="catalog/view/theme/default/image/remove.png" data-product-key="<?php echo $product['key']; ?>" alt="<?php echo $button_remove; ?>" title="<?php echo $button_remove; ?>" />
                        </span>
                    </td>
                    <td class="price"><?php echo $product['price']; ?></td>
                    <td class="total"><?php echo $product['total']; ?></td>
                </tr>
            <?php } ?>
            <?php foreach ($vouchers as $voucher) { ?>
                <tr>
                    <td class="text-left"><?php echo $voucher['description']; ?></td>
                    <td class="text-left"></td>
                    <td class="text-right">1</td>
                    <td class="text-right"><?php echo $voucher['amount']; ?></td>
                    <td class="text-right"><?php echo $voucher['amount']; ?></td>
                </tr>
            <?php } ?>
            </tbody>
            <tfoot>
            <?php foreach ($totals as $total) { ?>
                <tr>
                    <td colspan="4" class="text-right"><?php echo $total['title']; ?>:</td>
                    <td class="text-right"><?php echo $total['text']; ?></td>
                </tr>
            <?php } ?>
            </tfoot>
        </table>
    </div>
    <div id="payment-confirm-button" class="payment-<?php echo Journal2Utils::getProperty($this->session->data, 'payment_method.code'); ?>">
        <h2 class="secondary-title"><?php echo $this->journal2->settings->get('one_page_lang_payment_details', 'Payment Details'); ?></h2>
        <?php echo $payment; ?>
    </div>
</div>

