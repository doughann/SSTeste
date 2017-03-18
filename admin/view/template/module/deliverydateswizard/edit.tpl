<?php echo $header; ?>
<link rel="stylesheet" type="text/css" href="view/template/module/deliverydateswizard/style.css" />
<div id="content">
    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
	        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <div class="box">
        <div class="heading">
            <h1><?php echo $heading_title; ?></h1>
        </div>
        <div class="content">
			<div class="success" style="display:none;">Success: settings have been saved</div>		
			
			<div id="listShippingMethods" class="htabs">
				<a id="" class="selected">All</a>
				<?php foreach($shipping_methods as $method) : ?>
					<a id="<?php echo $method['code'];?>"><?php echo $method['name'];?></a>
				<?php endforeach; ?>
			</div>
			
			<div id="settings">
				<form name="frmSettings">
					<input type="text" name="shipping_method_code" value="" />
					<table width="60%" cellpadding="0" cellspacing="0" border="0" class="form">
					<tr>
						<td width="10%"><b>Enabled</b></td>
						<td width="80%">
							<select name="enabled">
								<option value="1">Yes</option>
								<option value="0">No</option>
							</select>
						</td>
					</tr>
					<tr>
						<td><b>Required</b></td>
						<td>
							<select name="required">
								<option value="1">Yes</option>
								<option value="0">No</option>
							</select>
						</td>
					</tr>
					<tr>
						<td valign="top"><strong>Weekdays Blocked</strong></td>
						<td>
							<div id="weekdays">
								<div><input type="checkbox" name="weekdays[]" value="<?php echo date('w', strtotime('monday'));?>" id="<?php echo date('w', strtotime('monday'));?>" />Monday</div>
								<div><input type="checkbox" name="weekdays[]" value="<?php echo date('w', strtotime('tuesday'));?>" id="<?php echo date('w', strtotime('tuesday'));?>" />Tuesday</div>
								<div><input type="checkbox" name="weekdays[]" value="<?php echo date('w', strtotime('wednesday'));?>" id="<?php echo date('w', strtotime('wednesday'));?>" />Wednesday</div>
								<div><input type="checkbox" name="weekdays[]" value="<?php echo date('w', strtotime('thirsday'));?>" id="<?php echo date('w', strtotime('thursday'));?>" />Thursday</div>
								<div><input type="checkbox" name="weekdays[]" value="<?php echo date('w', strtotime('friday'));?>" id="<?php echo date('w', strtotime('friday'));?>" />Friday</div>
								<div><input type="checkbox" name="weekdays[]" value="<?php echo date('w', strtotime('saturday'));?>" id="<?php echo date('w', strtotime('saturday'));?>" />Saturday</div>
								<div><input type="checkbox" name="weekdays[]" value="<?php echo date('w', strtotime('sunday'));?>" id="<?php echo date('w', strtotime('sunday'));?>" />Sunday</div>
							</div>
						</td>
					</tr>
					<tr>
						<td valign="top"><strong>Min / Max Days</strong></td>
						<td id="min_max_days">
							<table width="100%" cellpadding="0" cellspacing="0" border="0">
							<tr>
								<td width="200"><label>Min days from order date</label></td>
								<td width="100">
									<input name="min_days" type="text" value="" maxlength="3" size="4" />
								</td>
								<td width="80">Cut off time</td>
								<td>
									<input type="checkbox" name="cut_off_time_enabled" value="1" />
									<div id="cut_off_time_panel" style="display:inline-block;">
										<?php if (isset($clockList->hours)) : ?>									
											<select name="cut_off_time_hours">										
												<?php foreach($clockList->hours as $key=>$value) : ?>
													<option value="<?php echo $value;?>"><?php echo $value;?></option>
												<?php endforeach; ?>
											</select>
										<?php endif; ?>
										<?php if (isset($clockList->minutes)) : ?>
											<select name="cut_off_time_minutes">										
												<?php foreach($clockList->minutes as $key=>$value) : ?>
													<option value="<?php echo $value;?>"><?php echo $value;?></option>
												<?php endforeach; ?>
											</select>
										<?php endif; ?>
									</div>
								</td>
							</tr>
							<tr>
								<td width="200"><label>Max days from order date</label></td>
								<td colspan="2">
									<input name="max_days" type="text" value="" maxlength="3" size="4" />
								</td>
							</tr>
							</table>							
						</td>
					</tr>					
					<tr>
						<td valign="top"><strong>Dates to block</strong></td>
						<td id="widgetDaysBlocked">
							<?php echo $daysToBlockDisplay;?>
						</td>
					</tr>
					<tr>
						<td valign="top">
							<strong>Delivery Times</strong><br />
							<i>Allow customers to select delivery time during checkout</i>
						</td>
						<td id="widgetDeliveryTimes">
							<?php echo $widget_delivery_times;?>
						</td>
					</tr>					
					<tr>
						<td valign="top"><strong>Front end translations</strong></td>
						<td class="text">
							<div id="languages" class="htabs">
								<?php foreach ($languages as $language) { ?>
									<a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
								<?php } ?>
							</div>
							<?php foreach ($languages as $language) { ?>
								<div id="language<?php echo $language['language_id']; ?>">
									<table class="form">
									<tr>
										<td>Label on invoices and emails</td>
										<td>
											<input type="text" name="textlabel_<?php echo $language['language_id'];?>" value="" size="30" />
										</td>
									</tr>
                                    <tr>
                                        <td>
                                            <label class="control-label">Required error</label>
                                        </td>
                                        <td>
                                            <input type="text" name="textrequirederror_<?php echo $language['language_id'];?>" value="" size="60" class="form-control" />
                                        </td>
                                    </tr>
									<tr>
										<td colspan="2">
											HTML Content block diaplyed before calender<br />
											<br>
											<textarea name="textselect_<?php echo $language['language_id'];?>"></textarea>
										</td>
									</tr>	
									<!--														
									<tr>
										<td>Select delivery date text</td>
										<td>
											<input type="text" name="textselect_<?=$language['language_id'];?>" value="" size="60" />
										</td>
									</tr>
									-->
									<tr>
										<td>Selected date</td>
										<td>
											<input type="text" name="textselecteddate_<?php echo $language['language_id'];?>" value="" size="60" />
										</td>
									</tr>									
									</table>
								</div>
							<?php } ?>													
						</td>
					</tr>
					<tr>
						<td width="100%" colspan="2">
							<a href="" id="btnSave" type="button" value="Save" class="button" />Save Settings</a>
						</td>
					</tr>					
					</table>
				</form>								
			</div>
		
        </div>
    </div>
</div>
<script>
	var ajaxURL = "<?php echo $ajaxURL;?>";
</script>

<?php echo $admin_url;?>
<script type="text/javascript" src="<?php echo $admin_url;?>view/javascript/ckeditor/ckeditor.js"></script> 
<script src="<?php echo $admin_url;?>view/template/module/deliverydateswizard/widget_delivery_times.js"></script>
<script src="<?php echo $admin_url;?>view/template/module/deliverydateswizard/edit.js"></script>


<script>
	$(document).ready(function() {
		CKEditor_loaded = false;
		
		$(document).on('focus',".date", function(){
			$(this).datepicker({
				dateFormat: 'yy-mm-dd'
			});
		});		
		
		<?php foreach ($languages as $language) { ?>
		CKEDITOR.replace('textselect_<?php echo $language['language_id']; ?>', {
			filebrowserBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserImageBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserFlashBrowseUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserImageUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>',
			filebrowserFlashUploadUrl: 'index.php?route=common/filemanager&token=<?php echo $token; ?>'
		});
		<?php } ?>
		
		$(document).ready(function() {
			CKEDITOR.on('instanceReady', function(){ 
				CKEditor_loaded = true;
				var ddwEdit = new DDWEditClass();
			});
		}); 		
		
	});
</script>

<?php echo $footer; ?>