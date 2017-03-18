<?php echo $header; ?>
<style>
table.bt-settings td{
    padding: 10px;
}
</style>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/module.png" alt="" /> <?php echo $lang->get('heading_title'); ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $lang->get('button_save'); ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $lang->get('button_cancel'); ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      
      <table class="bt-settings">
          <tr>
            <td><?php echo $lang->get('entry_display_type'); ?>&nbsp;&nbsp;&nbsp;<select name="buy_together_options[display]">
                <?php if ($buy_together_display) { ?>
                    <option value="0"><?php echo $lang->get('text_display_images'); ?></option>
                    <option value="1" selected="selected"><?php echo $lang->get('text_display_both'); ?></option>                
                <?php } else { ?>
                    <option value="0" selected="selected"><?php echo $lang->get('text_display_images'); ?></option>
                    <option value="1"><?php echo $lang->get('text_display_both'); ?></option>
                <?php } ?>
              </select></td>
            <td><?php echo $lang->get('entry_title_short'); ?>&nbsp;&nbsp;&nbsp;<input type="text" name="buy_together_options[titleWidth]" value="<?php echo $buy_together_title_width; ?>" size="1" /></td>
            <td><?php echo $lang->get('entry_image_size'); ?>&nbsp;&nbsp;&nbsp;
            <input type="text" name="buy_together_options[imageWidth]" value="<?php echo $buy_together_image_width; ?>" size="1" /> x
            <input type="text" name="buy_together_options[imageHeight]" value="<?php echo $buy_together_image_height; ?>" size="1" />
            </td>
          </tr>
          </table>
      
        <table id="module" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $lang->get('entry_position'); ?></td>
              <td class="left"><?php echo $lang->get('entry_status'); ?></td>
              <td class="right"><?php echo $lang->get('entry_sort_order'); ?></td>
            </tr>
          </thead>
          <tbody id="module-row0">
            <tr>
              <td class="left">
                    <input type="hidden" name="buy_together_module[0][layout_id]" value="2">
                  <select name="buy_together_module[0][position]">
                  <?php if ($module['position'] == 'content_top') { ?>
                  <option value="content_top" selected="selected"><?php echo $lang->get('text_content_top'); ?></option>
                  <?php } else { ?>
                  <option value="content_top"><?php echo $lang->get('text_content_top'); ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'content_bottom') { ?>
                  <option value="content_bottom" selected="selected"><?php echo $lang->get('text_content_bottom'); ?></option>
                  <?php } else { ?>
                  <option value="content_bottom"><?php echo $lang->get('text_content_bottom'); ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'column_left') { ?>
                  <option value="column_left" selected="selected"><?php echo $lang->get('text_column_left'); ?></option>
                  <?php } else { ?>
                  <option value="column_left"><?php echo $lang->get('text_column_left'); ?></option>
                  <?php } ?>
                  <?php if ($module['position'] == 'column_right') { ?>
                  <option value="column_right" selected="selected"><?php echo $lang->get('text_column_right'); ?></option>
                  <?php } else { ?>
                  <option value="column_right"><?php echo $lang->get('text_column_right'); ?></option>
                  <?php } ?>
                </select></td>
              <td class="left"><select name="buy_together_module[0][status]">
                  <?php if ($module['status']) { ?>
                  <option value="1" selected="selected"><?php echo $lang->get('text_enabled'); ?></option>
                  <option value="0"><?php echo $lang->get('text_disabled'); ?></option>
                  <?php } else { ?>
                  <option value="1"><?php echo $lang->get('text_enabled'); ?></option>
                  <option value="0" selected="selected"><?php echo $lang->get('text_disabled'); ?></option>
                  <?php } ?>
                </select></td>
              <td class="right"><input type="text" name="buy_together_module[0][sort_order]" value="<?php echo $module['sort_order']; ?>" size="3" /></td>
            </tr>
          </tbody>
        </table>
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>