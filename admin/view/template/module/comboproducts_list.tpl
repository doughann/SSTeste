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
  <?php if (isset($success)) { ?>
  <div class="success"><?php echo $success; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/product.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a href="<?php echo $insert; ?>" class="button"><?php echo $button_insert; ?></a><a onclick="$('form').submit();" class="button"><?php echo $button_delete; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $delete; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="list">
          <thead>
                <tr>
					<td style="width: 1px;" class="text-center"><input type="checkbox" onclick="$('input[name*=\'selected\']').prop('checked', this.checked);" /></td>
					<td class="text-left"><?php echo $entry_combo_name; ?></td>
					<td class="text-left"><?php echo $entry_product_name; ?></td>
					<td class="text-left"><?php echo $entry_discount; ?></td>
					<td class="text-left"><?php echo $entry_display_position; ?></td>
					<td class="text-left"><?php echo $entry_priority; ?></td>
					<td></td>
                </tr>
              </thead>
              <tbody>
                <?php if ($combos) { ?>
                <?php foreach ($combos as $combo) { ?>
                <tr>
                  <td class="text-center"><?php if (in_array($combo['combo_id'], $selected)) { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $combo['combo_id']; ?>" checked="checked" />
                    <?php } else { ?>
                    <input type="checkbox" name="selected[]" value="<?php echo $combo['combo_id']; ?>" />
                    <?php } ?></td>
                  <td class="text-left"><?php echo $combo['combo_name']; ?></td>
				  <td class="text-left">
					<?php foreach ($combo['products'] as $product) {?>
						<ul>
							<li><?php echo $product; ?></li>
						</ul>
					<?php }?>
				  </td>
				  <td class="text-left">
					<?php if ($combo['discount_type'] == 'fixed amount') { ?>
						<?php echo $combo['discount_type'].": ".$combo['discount_number']." ".$_SESSION['currency']; ?>
					<?php } else {?>
						<?php echo $combo['discount_type'].": ".$combo['discount_number']."% of 'Price'"; ?>
					<?php }?>
				  </td>
				  <td class="text-left">
					<input type="checkbox" name="<?php echo $text_detail_page ?>" <?php if ($combo['display_detail']) echo 'checked="checked"'; ?> disabled="disabled" /> <?php echo $text_detail_page ?>
					<?php if ($combo['category_list']) { ?>
					</br>
					<label class="control-label"><?php echo $entry_category.": "; ?></label>
					<div class="form-control well well-sm" name="category" rows="2" disabled="disabled" style="height: auto">
					<?php foreach ($combo['category_list'] as $category_list) {;?>
						<?php echo $category_list; ?>
						</br>
					<?php }?>
					</div>
					<?php } ?>
				  </td>
				  <td class="text-center">
					<?php echo $combo['priority']; ?>
				  </td>
                  <td class="text-center">[<a href="<?php echo $combo['edit']; ?>" data-toggle="tooltip" title="<?php echo $button_edit; ?>" class="btn btn-primary">Edit</a>]</td>
                </tr>
                <?php } ?>
				<tr>
                  <td class="text-left" colspan="7">
					<ul>
					<li><?php echo $text_next_version; ?></li>
					<!--<li><?php echo $combo['warning']; ?></li>-->
					</ul>
				  </td>
                </tr>
                <?php } else { ?>
                <tr>
                  <td class="text-center" colspan="7"><strong><?php echo $text_no_results; ?></strong></td>
                </tr>
                <?php } ?>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</div>x
<?php echo $footer; ?>