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

  <?php $payment_type_row_no = 0; ?>

  <div class="box">

    <div class="heading">

      <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?>&nbsp;<?php echo 'V'.POS_VERSION; ?></h1>

      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>

	</div>

    <div class="content">

	  <div style="width:600px">

      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table id="payment_type_table" class="list">

          <thead>
			<tr>
				<td colspan="2" class="left" style="background-color: #E7EFEF;"><?php echo $text_payment_type_setting; ?></td>
			</tr>
            <tr>

              <td class="left" width='70%'><?php echo $text_order_payment_type; ?></td>

              <td class="right" width='30%'><?php echo $text_action; ?></td>

            </tr>

          </thead>

		  <tbody id="payment_type_list">

            <tr class='filter' id="payment_type_add">

              <td class="left" width="70%"><input type="text" name="payment_type" id="payment_type" style="width: 95%;" value="" onkeypress="return addPaymentOnEnter(event)" /></td>

              <td class="right" width="30%"><a id="button_add_payment_type" onclick="addPaymentType();" class="button"><?php echo $button_add_type; ?></a></td>

            </tr>

		<?php



		if (isset($payment_types)) {

			foreach ($payment_types as $payment_type=>$payment_name) {

		?>

		<tr id="<?php echo 'payment_type_'.$payment_type_row_no; ?>">

			<td class="left" width="70%"><?php echo $payment_name; ?></td>

			<td class="right" width="30%">
				<?php if (!$payment_type || $payment_type != 'cash' && $payment_type != 'credit_card') { ?>
				<a onclick="deletePaymentType('<?php echo 'payment_type_'.$payment_type_row_no; ?>');" class="button"><?php echo $button_remove; ?></a>
				<?php } ?>
				<input type="hidden" name="POS_payment_types[<?php echo $payment_type; ?>]" value="<?php echo $payment_name; ?>"/>
			</td>

		</tr>

		<?php $payment_type_row_no ++; }} ?>

          </tbody>

        </table>

        <table id="page_display" class="list">
          <thead>
			<tr>
				<td colspan="2" class="left" style="background-color: #E7EFEF;"><?php echo $text_display_setting; ?></td>
			</tr>
          </thead>
		  <tbody>
			<tr><td colspan="2" class="left">
				<input type="checkbox" name="display_once_login" value="<?php echo $display_once_login; ?>" <?php if($display_once_login=='1') { ?>checked="checked"<?php } ?> />&nbsp;
				<?php echo $text_display_once_login; ?>
			</td></tr>
			<tr>
				<td class="left" valign="center"><?php echo $column_exclude; ?></td>
				<td class="left" valign="center"><div class="scrollbox">
					<?php $class = 'odd'; ?>
					<?php foreach ($user_groups as $user_group) { ?>
					<?php $class = ($class == 'even' ? 'odd' : 'even'); ?>
					<div class="<?php echo $class; ?>">
					  <?php if (in_array($user_group['user_group_id'], $excluded_groups)) { ?>
					  <input type="checkbox" name="excluded_groups[]" value="<?php echo $user_group['user_group_id']; ?>" checked="checked" />
					  <?php echo $user_group['name']; ?>
					  <?php } else { ?>
					  <input type="checkbox" name="excluded_groups[]" value="<?php echo $user_group['user_group_id']; ?>" />
					  <?php echo $user_group['name']; ?>
					  <?php } ?>
					</div>
					<?php } ?>
				  </div>
				  <a onclick="$(this).parent().find(':checkbox').attr('checked', true);"><?php echo $text_select_all; ?></a> / <a onclick="$(this).parent().find(':checkbox').attr('checked', false);"><?php echo $text_unselect_all; ?></a></td>
			</tr>
          </tbody>
        </table>
      </form>
    </div>
	</div>

   </div>

</div>

<script type="text/javascript">

$('input[name=\'display_once_login\']').live('click', function() {
	if ($(this).is(':checked')) {
		$(this).attr('value', '1');
	} else {
		$(this).attr('value', '0');
	}
});

var payment_type_row = <?php echo $payment_type_row_no; ?>;



	function addPaymentType() {

		var checkValue = checkPaymentType();

		if (checkValue == 1) {

			// already in the list

			warning_tips = '<img src="view/image/warning.png" id="type_warning_tips" alt="<?php echo $text_type_already_exist; ?>" title="<?php echo $text_type_already_exist; ?>" />';

			$('#type_warning_tips').remove();

			$(warning_tips).insertAfter($('#payment_type'));

			return false;

		}

		$('#type_warning_tips').remove();

		var value = $('#payment_type').val();

		var new_payment_type_html = '<tr id="payment_type_' + payment_type_row + '"><td class="left" width="70%">' + value + '</td><td class="right" width="30%"><a onclick="deletePaymentType(\'payment_type_' + payment_type_row + '\');" class="button" size=2><?php echo $button_remove; ?></a></td><input type="hidden" name="POS_payment_types[' + payment_type_row + ']" value="' + value + '"/></tr>';

		$(new_payment_type_html).insertAfter('#payment_type_add');

		$('#payment_type').val("");

		

		payment_type_row ++;

	};
	
	function deletePaymentType(rowId) {

		$('#'+rowId).remove();

	};

	function checkPaymentType() {

		retValue = 0;

		curValue = $('#payment_type').val().toLowerCase();

		$("#payment_type_table tr").each(function(){

			value = $(this).find('td:first-child').text().toLowerCase();

			if (curValue == value) {

				retValue = 1;

			}

		});

		return retValue;

	};

	function addPaymentOnEnter(e) {
		var key;
		if (window.event)
			key = window.event.keyCode; //IE
		else
			key = e.which; //Firefox & others

		if(key == 13) {
			addPaymentType();
			return false;
		}
	}

</script> 

<?php echo $footer; ?>