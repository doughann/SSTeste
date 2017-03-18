<?php
/*
 * Author: minhdqa
 * Mail: minhdqa@gmail.com 
 */
?>
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
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/product.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form-combo').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form-combo" >
          <div class="table-responsive">
            <table class="form table table-bordered table-hover">
              <thead>
                <tr>
					<td class="text-left"><span class="required">*</span><?php echo $entry_combo_name; ?></td>
					<td class="text-left"><span class="required">*</span><?php echo $entry_product_name; ?></td>
					<td class="text-left"><?php echo $entry_discount; ?></td>
					<td class="text-left"><?php echo $entry_display_position; ?></td>
					<td class="text-left"><?php echo $entry_priority; ?></td>
                </tr>
              </thead>
              <tbody> 
                <tr>
                  <td class="text-left" style="vertical-align: top;">
					<div class="form-group required <?php if (isset($error_combo_name)) {echo 'has-error';}?>">
					<input type="text" name="combo_name" value="<?php echo $combo['combo_name']; ?>" class="form-control" id="combo-name"/>
					<?php if (isset($error_combo_name)) { ?>
						<div class="text-danger"><?php echo $error_combo_name; ?></div>
					<?php } ?>
					</div>
				  </td>
				  <td class="text-left" style="vertical-align: top;">
					<div class="form-group required <?php if ($error_combo_products) {echo 'has-error';}?>">
					  <input type="text" name="combo-product" value="" id="input-product" class="form-control" />
					  <div id="combo-products" class="scrollbox">
						<?php foreach ($combo['combo_products'] as $combo_products) { ?>
						<div id="combo-products-<?php echo $combo_products['product_id']; ?>">
						  <?php echo $combo_products['product_name']; ?>
						  <img src="view/image/delete.png" alt="" />
						  <input type="hidden" name="combo_products[]" value="<?php echo $combo_products['product_id']; ?>" />
						</div>
						<?php } ?>
					  </div>
					  <?php if ($error_combo_products) { ?>
						<div class="text-danger"><?php echo $error_combo_products; ?></div>
					  <?php } ?>
					</div>  
				  </td>
				  <td class="text-left" style="vertical-align: top;">
					<div>
						<label class="col-sm-3 control-label"><?php echo $text_discount_type; ?></label>
						<div class="col-sm-9">
							<select name="discount_type" class="form-control" style="width:200px;">
								<option value="fixed amount" 	<?php if($combo['discount_type'] == "fixed amount") echo "selected "; ?>><?php echo $text_fixed; ?></option>
								<option value="percentage" 	<?php if($combo['discount_type'] == "percentage") echo "selected "; ?>><?php echo $text_percent; ?></option>
							</select>
							<p></p>
						</div>
					</div>
					<div class="form-group <?php if ($error_discount_number) {echo 'has-error';}?>">
						<label class="col-sm-3 control-label"><span class="required">*</span><?php echo $text_discount_numb; ?></label>
						<div class="col-sm-9">
							<input type="text" name="discount_number" value="<?php echo $combo['discount_number']; ?>" id="input-discount" class="form-control" />
							<?php if ($error_discount_number) { ?>
								<div class="text-danger"><?php echo $error_discount_number; ?></div>
							<?php } ?>
						</div>
					</div>
				  </td>
				  <td class="text-left" style="vertical-align: top;">
					<div>
						<input type="checkbox" name="display_detail" value="1" <?php if ($combo['display_detail']) echo "checked=checked"; ?> /> <?php echo $text_detail_page ?>
					</div>
					<div>
					<label class="col-sm-3 control-label"><?php echo $entry_category.": "; ?></label>
					<select multiple name="combo_category[]" class="col-sm-9">
					<?php foreach ($combo['category_list'] as $category_id => $category_list) {;?>
						<option value="<?php echo $category_id;?>" <?php if (in_array($category_id,$combo['combo_category'])) echo "selected=selected"; ?>><?php echo $category_list; ?></option>
					<?php }?>
					</select>
					</div>
					
				  </td>
				  <td class="text-left">
					<div>
						<input type="text" name="priority" value="<?php echo $combo['priority']; ?>" id="input-priority" class="form-control" style="width:40px;"/>
					</div>
				  </td>
                </tr>
              </tbody>
            </table>
			<div>
				<input type="checkbox" name="override" value="1" <?php if ($combo['override']) echo "checked=checked"; ?> /> <?php echo $text_override ?>
			</div>
			<div>
				<strong><?php echo $text_next_version; ?></strong>
			</div>
          </div>
        </form>
    </div>
  </div>
</div>
<script type="text/javascript" language="javascript"><!--	
$('input[name=\'combo-product\']').autocomplete({
	delay: 500,
	source: function(request, response) {
		$.ajax({
			url: 'index.php?route=catalog/product/autocomplete&token=<?php echo $token; ?>&filter_name=' +  encodeURIComponent(request.term),
			dataType: 'json',
			success: function(json) {		
				response($.map(json, function(item) {
					return {
						label: item.name+' ('+item['model']+')',
						value: item.product_id
					}
				}));
			}
		});
	}, 
	select: function(event, ui) {
		$('#combo-products' + ui.item.value).remove();
		
		$('#combo-products').append('<div id="combo-products-' + ui.item.value + '">' + ui.item.label + '<img src="view/image/delete.png" alt="" /><input type="hidden" name="combo_products[]" value="' + ui.item.value + '" /></div>');

		$('#combo-products div:odd').attr('class', 'odd');
		$('#combo-products div:even').attr('class', 'even');
				
		return false;
	},
	focus: function(event, ui) {
      return false;
   }
});

$('#combo-products div img').live('click', function() {
	$(this).parent().remove();
	
	$('#combo-products div:odd').attr('class', 'odd');
	$('#combo-products div:even').attr('class', 'even');	
});	
//--></script> 