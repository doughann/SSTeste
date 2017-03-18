<?php
//==============================================================================
// Checkout Survey v155.1
//
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
//==============================================================================
?>

<?php echo $header; ?>
<style type="text/css">
	.ui-dialog {
		position: fixed;
	}
	#embed-code {
		background: #F8F8F8;
		cursor: pointer;
		font-family: monospace;
		width: 100%;
	}
	ul.help {
		line-height: 1.5;
		margin-top: 0;
		padding-left: 20px;
	}
	.list thead td {
		height: 24px;
	}
	.list td {
		border: 1px solid #DDD;
		width: 1px;
	}
	.list input {
		width: 95%;
		min-width: 100px;
	}
	.list tfoot td {
		background: #EEE;
	}
	.sort-handle {
		cursor: move;
	}
</style>
<?php if ($version > 149) { ?>
<div id="content">
	<div class="breadcrumb">
		<?php foreach ($breadcrumbs as $breadcrumb) { ?>
			<?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
		<?php } ?>
	</div>
<?php } ?>
<?php if ($error_warning) { ?><div class="warning"><?php echo $error_warning; ?></div><?php } ?>
<?php if ($success) { ?><div class="success"><?php echo $success; ?></div><?php } ?>
<div class="box">
	<?php if ($version < 150) { ?><div class="left"></div><div class="right"></div><?php } ?>
	<div class="heading">
		<h1 style="padding: 10px 2px 0"><img src="view/image/<?php echo $type; ?>.png" alt="" style="vertical-align: middle" /> <?php echo $heading_title; ?></h1>
		<div class="buttons">
			<a class="button" onclick="$('#form').submit()"><?php echo $button_save_exit; ?></a>
			<a class="button" onclick="save()"><?php echo $button_save_keep_editing; ?></a>
			<a class="button" onclick="location = '<?php echo $exit; ?>'"><?php echo $button_cancel; ?></a>
		</div>
	</div>
	<div class="content">
		<form action="" method="post" enctype="multipart/form-data" id="form">
			<table class="form">
				<tr>
					<td style="width: 250px"><?php echo $entry_embed_code; ?></td>
					<td><input type="text" id="embed-code" readonly="readonly" onclick="this.select()" value="<?php echo '<?php include_once(DIR_APPLICATION . \'view/theme/default/template/' . $type . '/' . $name . '.tpl\'); ?>'; ?>" /></td>
				</tr>
				<tr style="background: #E4EEF7">
					<td colspan="2"><strong><?php echo $entry_general_settings; ?></strong></td>
				</tr>
				<tr>
					<td><?php echo $entry_status; ?></td>
					<td><select name="<?php echo $name; ?>_status">
							<option value="1"><?php echo $text_enabled; ?></option>
							<option value="0" <?php if (empty(${$name.'_status'})) echo 'selected="selected"'; ?>><?php echo $text_disabled; ?></option>
						</select>
						<a href="<?php echo HTTPS_SERVER . 'index.php?route=report/' . $name . '&token=' . $token; ?>" class="button" style="float: right"><span><?php echo $button_view_report; ?></span></a>
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_sort_order; ?></td>
					<td><input type="text" size="1" name="<?php echo $name; ?>_sort_order" value="<?php echo (isset(${$name.'_sort_order'})) ? ${$name.'_sort_order'} : 99; ?>" /></td>
				</tr>
				<tr>
					<td><?php echo $entry_only_survey_first; ?></td>
					<td><select name="<?php echo $name; ?>_first_time">
							<option value="0"><?php echo $text_no; ?></option>
							<option value="1" <?php if (!empty(${$name.'_first_time'})) echo 'selected="selected"'; ?>><?php echo $text_yes; ?></option>
						</select>
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_heading; ?></td>
					<td><?php foreach ($languages as $language) { ?>
							<div style="white-space: nowrap">
								<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
								<input type="text" size="100" name="<?php echo $name; ?>_heading[<?php echo $language['code']; ?>]" value="<?php echo (!empty(${$name.'_heading'}[$language['code']])) ? ${$name.'_heading'}[$language['code']] : $heading_title; ?>" />
							</div>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_prequestion_text; ?></td>
					<td><?php foreach ($languages as $language) { ?>
							<div style="white-space: nowrap">
								<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
								<input type="text" size="100" name="<?php echo $name; ?>_text[<?php echo $language['code']; ?>]" value="<?php echo (!empty(${$name.'_text'}[$language['code']])) ? ${$name.'_text'}[$language['code']] : ''; ?>" />
							</div>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_multiselect_size; ?></td>
					<td><input type="text" size="1" name="<?php echo $name; ?>_multiselect_size" value="<?php echo (isset(${$name.'_multiselect_size'})) ? ${$name.'_multiselect_size'} : 5; ?>" /></td>
				</tr>
				<tr>
					<td><?php echo $entry_error_message; ?></td>
					<td><?php foreach ($languages as $language) { ?>
							<div style="white-space: nowrap">
								<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" />
								<input type="text" size="100" name="<?php echo $name; ?>_error[<?php echo $language['code']; ?>]" value="<?php echo (!empty(${$name.'_error'}[$language['code']])) ? ${$name.'_error'}[$language['code']] : ''; ?>" />
							</div>
						<?php } ?>
					</td>
				</tr>
				<tr>
					<td><?php echo $entry_button_selector; ?></td>
					<td><input type="text" size="10" name="<?php echo $name; ?>_button" value="<?php echo (isset(${$name.'_button'})) ? ${$name.'_button'} : '.button'; ?>" /></td>
				</tr>
				<tr style="background: #E4EEF7">
					<td colspan="2"><strong><?php echo $entry_questions; ?></strong></td>
				</tr>
				<tr>
					<td colspan="2">
						<ul class="help">
							<li><strong><?php echo $entry_type; ?>:</strong> <?php echo $help_type; ?></li>
							<li><strong><?php echo $entry_required; ?>:</strong> <?php echo $help_required; ?></li>
							<li><strong><?php echo $entry_question; ?>:</strong> <?php echo $help_question; ?></li>
							<li><strong><?php echo $entry_responses; ?>:</strong> <?php echo $help_responses; ?></li>
							<li><strong><?php echo $entry_other_response; ?>:</strong> <?php echo $help_other_response; ?></li>
							<li><strong><?php echo $entry_line_item_text; ?>:</strong> <?php echo $help_line_item_text; ?></li>
						</ul>
						<table class="list" id="questions">
						<thead>
							<tr>
								<td class="left"></td>
								<td class="left"><?php echo $entry_type; ?></td>
								<td class="left"><?php echo $entry_required; ?></td>
								<td class="left"></td>
								<td class="left" style="width: 20%"><?php echo $entry_question; ?></td>
								<td class="left" style="width: 50%"><?php echo $entry_responses; ?></td>
								<td class="left"><?php echo $entry_other_response; ?></td>
								<td class="left"><?php echo $entry_line_item_text; ?></td>
								<td class="left"></td>
							</tr>
						</thead>
						<tbody>
							<?php $count = (isset(${$name.'_type'})) ? count(${$name.'_type'}) : 1; ?>
							<?php for ($i = 0; $i < $count; $i++) { ?>
								<tr <?php if (!$i) echo 'style="display: none"'; ?>>
									<td class="left sort-handle"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></td>
									<td class="left">
										<select name="<?php echo $name; ?>_type[]">
												<option value="checkbox" <?php if ($i && ${$name.'_type'}[$i] == 'checkbox') echo 'selected="selected"'; ?>><?php echo $text_checkboxes; ?></option>
												<option value="date" <?php if ($i && ${$name.'_type'}[$i] == 'date') echo 'selected="selected"'; ?>><?php echo $text_date_field; ?></option>
												<option value="datetime" <?php if ($i && ${$name.'_type'}[$i] == 'datetime') echo 'selected="selected"'; ?>><?php echo $text_datetime_field; ?></option>
												<option value="multi" <?php if ($i && ${$name.'_type'}[$i] == 'multi') echo 'selected="selected"'; ?>><?php echo $text_multiselect_box; ?></option>
												<option value="radio" <?php if ($i && ${$name.'_type'}[$i] == 'radio') echo 'selected="selected"'; ?>><?php echo $text_radio_buttons; ?></option>
												<option value="select" <?php if ($i && ${$name.'_type'}[$i] == 'select') echo 'selected="selected"'; ?>><?php echo $text_select_dropdown; ?></option>
												<option value="text" <?php if ($i && ${$name.'_type'}[$i] == 'text') echo 'selected="selected"'; ?>><?php echo $text_text_field; ?></option>
												<option value="textarea" <?php if ($i && ${$name.'_type'}[$i] == 'textarea') echo 'selected="selected"'; ?>><?php echo $text_textarea_field; ?></option>
												<option value="time" <?php if ($i && ${$name.'_type'}[$i] == 'time') echo 'selected="selected"'; ?>><?php echo $text_time_field; ?></option>
										</select>
									</td>
									<td class="left">
										<select name="<?php echo $name; ?>_required[]">
											<option value="0" <?php if ($i && !${$name.'_required'}[$i]) echo 'selected="selected"'; ?>><?php echo $text_no; ?></option>
											<option value="1" <?php if ($i && ${$name.'_required'}[$i]) echo 'selected="selected"'; ?>><?php echo $text_yes; ?></option>
										</select>
									</td>
									<td class="left" style="line-height: 26px">
										<?php foreach ($languages as $language) { ?>
											<img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /><br />
										<?php } ?>
									</td>
									<td class="left">
										<?php foreach ($languages as $language) { ?>
											<input type="text" name="<?php echo $name; ?>_question[<?php echo $language['code']; ?>][]" value="<?php if ($i) echo ${$name.'_question'}[$language['code']][$i]; ?>" /><br />
										<?php } ?>
									</td>
									<td class="left">
										<?php foreach ($languages as $language) { ?>
											<input type="text" name="<?php echo $name; ?>_responses[<?php echo $language['code']; ?>][]" value="<?php if ($i) echo ${$name.'_responses'}[$language['code']][$i]; ?>" /><br />
										<?php } ?>
									</td>
									<td class="left">
										<?php foreach ($languages as $language) { ?>
											<input type="text" name="<?php echo $name; ?>_other[<?php echo $language['code']; ?>][]" value="<?php if ($i) echo ${$name.'_other'}[$language['code']][$i]; ?>" /><br />
										<?php } ?>
									</td>
									<td class="left">
										<?php foreach ($languages as $language) { ?>
											<input type="text" name="<?php echo $name; ?>_lineitem[<?php echo $language['code']; ?>][]" value="<?php if ($i) echo ${$name.'_lineitem'}[$language['code']][$i]; ?>" /><br />
										<?php } ?>
									</td>
									<td class="left"><a onclick="$(this).parent().parent().remove()"><img src="view/image/error.png" title="Remove" /></a></td>
								</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="9" class="left"><a onclick="addRow($(this))" class="button"><span><?php echo $button_add_question; ?></span></a></td>
							</tr>
						</tfoot>
						</table>
					</td>
				</tr>
				<tr style="background: #E4EEF7">
					<td colspan="2"><strong><?php echo $entry_historical_data; ?></strong></td>
				</tr>
				<tr>
					<td colspan="2">
						<div class="help" style="margin-bottom: 10px"><?php echo $help_historical_data; ?></div>
						<table class="list" id="historical-data">
						<thead>
							<tr>
								<td class="left" style="width: 1px"></td>
								<td class="left"><?php echo $entry_question; ?></td>
								<td class="left"><?php echo $entry_response; ?></td>
								<td class="left"><?php echo $entry_customer_responses; ?></td>
								<td class="left"><?php echo $entry_customer_sales; ?></td>
								<td class="left"><?php echo $entry_guest_responses; ?></td>
								<td class="left"><?php echo $entry_guest_sales; ?></td>
								<td class="left"><?php echo $entry_notes; ?></td>
								<td class="left" style="width: 1px"></td>
							</tr>
						</thead>
						<tbody>
							<?php $count = (isset(${$name.'_historical_response'})) ? count(${$name.'_historical_response'}) : 1; ?>
							<?php for ($i = 0; $i < $count; $i++) { ?>
								<tr <?php if (!$i) echo 'style="display: none"'; ?>>
									<td class="left sort-handle"><span class="ui-icon ui-icon-arrowthick-2-n-s"></span></td>
									<td class="left"><input type="text" name="<?php echo $name; ?>_historical_question[]" value="<?php if ($i) echo ${$name.'_historical_question'}[$i]; ?>" /></td>
									<td class="left"><input type="text" name="<?php echo $name; ?>_historical_response[]" value="<?php if ($i) echo ${$name.'_historical_response'}[$i]; ?>" /></td>
									<td class="left"><input type="text" name="<?php echo $name; ?>_historical_customer_responses[]" style="min-width: 50px; width: 85%" value="<?php if ($i) echo ${$name.'_historical_customer_responses'}[$i]; ?>" /></td>
									<td class="left"><input type="text" name="<?php echo $name; ?>_historical_customer_sales[]" style="min-width: 50px; width: 85%" value="<?php if ($i) echo ${$name.'_historical_customer_sales'}[$i]; ?>" /></td>
									<td class="left"><input type="text" name="<?php echo $name; ?>_historical_guest_responses[]" style="min-width: 50px; width: 85%" value="<?php if ($i) echo ${$name.'_historical_guest_responses'}[$i]; ?>" /></td>
									<td class="left"><input type="text" name="<?php echo $name; ?>_historical_guest_sales[]" style="min-width: 50px; width: 85%" value="<?php if ($i) echo ${$name.'_historical_guest_sales'}[$i]; ?>" /></td>
									<td class="left"><input type="text" name="<?php echo $name; ?>_historical_notes[]" value="<?php if ($i) echo ${$name.'_historical_notes'}[$i]; ?>" /></td>
									<td class="left"><a onclick="$(this).parent().parent().remove()"><img src="view/image/error.png" title="Remove" /></a></td>
								</tr>
							<?php } ?>
						</tbody>
						<tfoot>
							<tr>
								<td colspan="9" class="left"><a onclick="addRow($(this))" class="button"><span><?php echo $button_add_response; ?></span></a></td>
							</tr>
						</tfoot>
						</table>
					</td>
				</tr>
			</table>
		</form>
		<?php echo $copyright; ?>
	</div>
</div>
<?php if ($version < 150) { ?>
	<script type="text/javascript" src="view/javascript/jquery/ui/ui.sortable.js"></script>
<?php } else { ?>
	</div>
<?php } ?>
<script type="text/javascript"><!--
	$(document).ready(function(){
		$('#questions tbody').sortable({ handle: '.sort-handle' });
		$('#historical-data tbody').sortable({ handle: '.sort-handle' });
		$('.sort-handle').disableSelection();
	});
	
	function save() {
		$('<div></div>').dialog({
			title: '<?php echo $text_saving; ?>',
			closeOnEscape: false,
			draggable: false,
			modal: true,
			resizable: false,
			open: function(event, ui) {
				$('.ui-dialog').css('padding', '0px');
				$('.ui-dialog-content').hide();
				$('.ui-dialog-titlebar-close').hide();
			}
		}).dialog('open');
		
		$.ajax({
			type: 'POST',
			url: 'index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/save&token=<?php echo $token; ?>',
			data: $('#form :input'),
			success: function(success) {
				var title = (success) ? '<?php echo $text_saved; ?>' : '<?php echo $standard_error; ?>';
				var delay = (success) ? 1000 : 2000;
				
				$('.ui-dialog-content').dialog('option', 'title', title);
				setTimeout(function(){
					$('.ui-dialog-content').dialog('close');
				}, delay);
			}
		});
	}
	
	function addRow(element) {
		var table = element.parent().parent().parent().parent();
		var clone = table.find('tbody > tr:first-child').clone();
		clone.find('input[type="text"]').val('');
		clone.find(':selected').removeAttr('selected');
		table.find('tbody').append(clone).find('tr:last-child').show();
	}
//--></script>
<?php echo $footer; ?>