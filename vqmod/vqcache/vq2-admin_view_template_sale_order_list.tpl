<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <?php if ($success) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/order.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons">
	 <!-- <a onclick="$('#form').attr('action', '<?php echo $invoice; ?>'); $('#form').attr('target', '_blank'); $('#form').submit();" class="button"><?php echo $button_invoice; ?></a>-->


	  <a href="<?php echo $relatorio; ?>" class="button">Relatório</a>
	  <a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a>
    <a onclick="$('#form').attr('action', '<?php echo $delete; ?>'); $('#form').attr('target', '_self'); $('#form').submit();" class="button"><?php echo $button_delete; ?></a>
    <a onclick="$('#form').attr('action', '<?php echo $processapedido; ?>'); $('#form').attr('target', '_self'); $('#form').submit();" class="button">Processar</a>
  </div>
    </div>
    <div class="content">
      <form action="" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
            <tr>
              <td width="1" style="text-align: center;"><input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);" /></td>
              <td class="right"><?php if ($sort == 'o.order_id') { ?>
                <a href="<?php echo $sort_order; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_order_id; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_order; ?>"><?php echo $column_order_id; ?></a>
                <?php } ?></td>

              <td class="left"><?php if ($sort == 'customer') { ?>
                <a href="<?php echo $sort_customer; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_customer; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_customer; ?>"><?php echo $column_customer; ?></a>
                <?php } ?></td>

              <td class="left"><?php if ($sort == 'status') { ?>
                <a href="<?php echo $sort_status; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_status; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_status; ?>"><?php echo $column_status; ?></a>
                <?php } ?></td>

				<td class="right"><?php if ($sort == 'o.shipping_method') { ?>
                <a href="<?php echo $sort_shipping_method; ?>" class="<?php echo strtolower($order); ?>">Método da entrega</a>
                <?php } else { ?>
                <a href="<?php echo $sort_shipping_method; ?>">Método da entrega</a>
                <?php } ?></td>

				<td class="right"><?php if ($sort == 'o.ddw_delivery_date') { ?>
                <a href="<?php echo $sort_delivery_date; ?>" class="<?php echo strtolower($order); ?>">Data da entrega</a>
                <?php } else { ?>
                <a href="<?php echo $sort_delivery_date; ?>">Data da entrega</a>
                <?php } ?></td>

				<td class="right"><?php if ($sort == 'o.ddw_time_slot') { ?>
                <a href="<?php echo $sort_delivery_time; ?>" class="<?php echo strtolower($order); ?>">Hora da entrega</a>
                <?php } else { ?>
                <a href="<?php echo $sort_delivery_time; ?>">Hora da entrega</a>
                <?php } ?></td>



              <td class="right"><?php if ($sort == 'o.total') { ?>
                <a href="<?php echo $sort_total; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_total; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_total; ?>"><?php echo $column_total; ?></a>
                <?php } ?></td>




				<td class="right">Forma de pagamento</td>           
			
              <td class="left"><?php if ($sort == 'o.date_added') { ?>
                <a href="<?php echo $sort_date_added; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_added; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_added; ?>"><?php echo $column_date_added; ?></a>
                <?php } ?></td>
              <td class="left"><?php if ($sort == 'o.date_modified') { ?>
                <a href="<?php echo $sort_date_modified; ?>" class="<?php echo strtolower($order); ?>"><?php echo $column_date_modified; ?></a>
                <?php } else { ?>
                <a href="<?php echo $sort_date_modified; ?>"><?php echo $column_date_modified; ?></a>
                <?php } ?></td>
              <td class="right"><?php echo $column_action; ?></td>
            </tr>
          </thead>
          <tbody>
            <tr class="filter">
              <td></td>
              <td align="right"><input type="text" name="filter_order_id" value="<?php echo $filter_order_id; ?>" size="4" style="text-align: right;" /></td>
              <td><input type="text" name="filter_customer" value="<?php echo $filter_customer; ?>" /></td>
              <td><select name="filter_order_status_id">
                  <option value="*"></option>

                  <?php if ($filter_order_status_id == '0') { ?>
                  <option value="0" selected="selected"><?php echo $text_missing; ?></option>
                  <?php } else { ?>
                  <option value="0"><?php echo $text_missing; ?></option>
                  <?php } ?>
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select></td>
				<td align="right">
				<select name="filter_shipping_method">
				<option value="*"></option>
				<?php //print_r($shipping_method);
				foreach ($shipping_method as $order_method) {

				?>
                  <?php if ($order_method['shipping_method'] == $filter_shipping_method) { ?>
                  <option value="<?php echo $order_method['shipping_method']; ?>" selected="selected"><?php echo $order_method['shipping_method']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_method['shipping_method']; ?>"><?php echo $order_method['shipping_method']; ?></option>
                  <?php } ?>
                  <?php } ?>
				  </select>
				  </td>
				<td align="right">

					<input type="text" name="filter_delivery_date" value="<?php echo $filter_delivery_date; ?>" size="12" class="date" />


				</td>
			  <td align="right"> <select name="filter_delivery_time">
				<option value="*"></option>
				<?php //print_r($shipping_method);
				foreach ($ddelivery_time as $delivery_time) {

				?>
                  <?php if ($delivery_time['ddw_time_slot'] == $filter_delivery_time) { ?>
                  <option value="<?php echo $delivery_time['ddw_time_slot']; ?>" selected="selected"><?php echo $delivery_time['ddw_time_slot']; ?></option>
                  <?php } else { ?>
                  <option value="<?php echo $delivery_time['ddw_time_slot']; ?>"><?php echo $delivery_time['ddw_time_slot']; ?></option>
                  <?php } ?>
                  <?php } ?>
				  </select> </td>
              <td align="right"><input type="text" name="filter_total" value="<?php echo $filter_total; ?>" size="4" style="text-align: right;" /></td>

				<td></td>         
			
			  <td><input type="text" name="filter_date_added" value="<?php echo $filter_date_added; ?>" size="12" class="date" /></td>
              <td><input type="text" name="filter_date_modified" value="<?php echo $filter_date_modified; ?>" size="12" class="date" /></td>
              <td align="right"><a onclick="filter();" class="button"><?php echo $button_filter; ?></a></td>
            </tr>
            <?php if ($orders) { ?>
            <?php foreach ($orders as $order) {

			?>
            <tr>
              <td style="text-align: center;" style="background-color:#<?php echo $order['cor'];?>;"><?php if ($order['selected']) { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" checked="checked" />
                <?php } else { ?>
                <input type="checkbox" name="selected[]" value="<?php echo $order['order_id']; ?>" />
                <?php } ?></td>
              <td class="right" style="background-color:#<?php echo $order['cor'];?>;"><?php echo $order['order_id']; ?></td>
              <td class="left" style="background-color:#<?php echo $order['cor'];?>;"><?php echo $order['customer']; ?></td>
              <td class="left" style="background-color:#<?php echo $order['cor'];?>;"><?php echo $order['status']; ?></td>
              <td class="right" style="background-color:#<?php echo $order['cor'];?>;"><?php echo $order['shipping_method']; ?></td>
              <td class="right" style="background-color:#<?php echo $order['cor'];?>;"><?php $datedelivery = new DateTime($order['ddw_delivery_date']);echo $datedelivery->format('d/m/Y'); ?></td>
              <td class="right" style="background-color:#<?php echo $order['cor'];?>;"><?php echo $order['ddw_time_slot']; ?></td>
			  <td class="right" style="background-color:#<?php echo $order['cor'];?>;"><?php echo $order['total']; ?></td>
			  <td class="right" style="background-color:#<?php echo $order['cor'];?>;"><?php echo $order['payment_method']; ?> </td>

              <td class="left" style="background-color:#<?php echo $order['cor'];?>;"><?php echo $order['date_added']; ?></td>
              <td class="left" style="background-color:#<?php echo $order['cor'];?>;"><?php echo $order['date_modified']; ?></td>
              <td class="right" style="background-color:#<?php echo $order['cor'];?>;"><?php foreach ($order['action'] as $action) { ?>
                [ <a href="<?php echo $action['href']; ?>"><?php echo $action['text']; ?></a> ]
                <?php } ?></td>
            </tr>
            <?php } ?>
            <?php } else { ?>
            <tr>
              <td class="center" colspan="8"><?php echo $text_no_results; ?></td>
            </tr>
            <?php } ?>
          </tbody>
        </table>
      </form>
      <div class="pagination"><?php echo $pagination; ?></div>
    </div>
  </div>
</div>
<script type="text/javascript"><!--
function filter() {
	url = 'index.php?route=sale/order&token=<?php echo $token; ?>';

	var filter_order_id = $('input[name=\'filter_order_id\']').attr('value');

	if (filter_order_id) {
		url += '&filter_order_id=' + encodeURIComponent(filter_order_id);
	}

	var filter_customer = $('input[name=\'filter_customer\']').attr('value');

	if (filter_customer) {
		url += '&filter_customer=' + encodeURIComponent(filter_customer);
	}

	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').attr('value');

	if (filter_order_status_id != '*') {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
	}
	var filter_shipping_method = $('select[name=\'filter_shipping_method\']').attr('value');

	if (filter_shipping_method != '*') {
		url += '&filter_shipping_method=' + encodeURIComponent(filter_shipping_method);
	}

	var filter_delivery_time = $('select[name=\'filter_delivery_time\']').attr('value');

	if (filter_delivery_time != '*') {
		url += '&filter_delivery_time=' + encodeURIComponent(filter_delivery_time);
	}

	var filter_total = $('input[name=\'filter_total\']').attr('value');

	if (filter_total) {
		url += '&filter_total=' + encodeURIComponent(filter_total);
	}

	var filter_delivery_date = $('input[name=\'filter_delivery_date\']').attr('value');

	if (filter_delivery_date) {
		url += '&filter_delivery_date=' + encodeURIComponent(filter_delivery_date);
	}
	var filter_date_added = $('input[name=\'filter_date_added\']').attr('value');

	if (filter_date_added) {
		url += '&filter_date_added=' + encodeURIComponent(filter_date_added);
	}

	var filter_date_modified = $('input[name=\'filter_date_modified\']').attr('value');

	if (filter_date_modified) {
		url += '&filter_date_modified=' + encodeURIComponent(filter_date_modified);
	}

	location = url;
}
//--></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>



<script type="text/javascript"><!--
$.widget('custom.catcomplete', $.ui.autocomplete, {
	_renderMenu: function(ul, items) {
		var self = this, currentCategory = '';

		$.each(items, function(index, item) {
			if (item.category != currentCategory) {
				ul.append('<li class="ui-autocomplete-category">' + item.category + '</li>');

				currentCategory = item.category;
			}

			self._renderItem(ul, item);
		});
	}
});

$('input[name=\'filter_customer\']').catcomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=sale/customer/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {
				response($.map(json, function(item) {
					return {
						category: item.customer_group,
						label: item.name,
						value: item.customer_id
					}
				}));
			}
		});
	},
	select: function(event, ui) {
		$('input[name=\'filter_customer\']').val(ui.item.label);

		return false;
	},
	focus: function(event, ui) {
      	return false;
   	}
});
//--></script>
<?php echo $footer; ?>
