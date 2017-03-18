<?php echo $header; ?>
<style>
/* css for timepicker */
#ui-timepicker-div dl{ text-align: left; }
#ui-timepicker-div dl dt{ height: 25px; }
#ui-timepicker-div dl dd{ margin: -25px 0 10px 65px; }
.ui-datepicker-year { display: none; }
</style>

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
    <h1><img src="view/image/module.png" alt="" /> <?php echo $heading_title; ?></h1>
    <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
  </div>
  <div class="content">
    <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
      <table class="form">
        <tr>
          <td><?php echo $entry_status; ?></td>
          <td><select name="deliverydate_status">
              <?php if ($deliverydate_status) { ?>
              <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
              <option value="0"><?php echo $text_disabled; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_enabled; ?></option>
              <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
              <?php } ?>
            </select></td>
        </tr>
        <tr>
          <td><?php echo $entry_required; ?></td>
          <td><select name="deliverydate_required">
              <?php if ($deliverydate_required) { ?>
              <option value="1" selected="selected"><?php echo $text_yes; ?></option>
              <option value="0"><?php echo $text_no; ?></option>
              <?php } else { ?>
              <option value="1"><?php echo $text_yes; ?></option>
              <option value="0" selected="selected"><?php echo $text_no; ?></option>
              <?php } ?>
            </select>
          </td>
        </tr>
        <tr>
          <td><?php echo $entry_interval; ?></td>
          <td><input type="text" name="deliverydate_interval_days" value="<?php echo $deliverydate_interval_days; ?>" size="12" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_unavailable_after; ?></td>
          <td><input type="text" name="deliverydate_unavailable_after" value="<?php echo $deliverydate_unavailable_after; ?>" size="12" class="time" /></td>
        </tr>
        <tr>
          <td><?php echo $entry_display_same_day; ?></td>
          <td><select name="deliverydate_same_day">
              <?php if ($deliverydate_same_day) { ?>
      			  <option value="1" selected="selected"><?php echo $text_yes; ?></option>
      			  <option value="0"><?php echo $text_no; ?></option>
      			  <?php } else { ?>
      			  <option value="1"><?php echo $text_yes; ?></option>
      			  <option value="0" selected="selected"><?php echo $text_no; ?></option>
      			  <?php } ?>
      			</select>
          </td>
        </tr>

        <tr>
          <td><?php echo $text_custom; ?></td>
          <td><?php if ($deliverydate_custom) { ?>
              <input name="deliverydate_custom" type="checkbox" checked="checked" id="deliverydate_custom" />
              <?php } else { ?>
              <input name="deliverydate_custom" type="checkbox" id="deliverydate_custom" />
              <?php } ?>
          </td>
        </tr>

        <tr id="custom_delivery" style="display:none;">
          <td colspan="2">
            <div id="languages" class="htabs">
              <?php foreach ($languages as $language) { ?>
              <a tab="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
    		  <?php } ?>
            </div>
            <?php foreach ($languages as $language) { ?>
            <div id="language<?php echo $language['language_id']; ?>">
              <table class="form">
                <tr>
                  <td><span class="required">*</span> <?php echo $entry_custom_same_day; ?></td>
                  <td><textarea name="deliverydate_custom_same_day[<?php echo $language['language_id']; ?>][text]" cols="40" rows="5"><?php echo isset($deliverydate_custom_same_day[$language['language_id']]) ? $deliverydate_custom_same_day[$language['language_id']]['text'] : ''; ?></textarea></td>
                  <?php if (isset($error_text[$language['language_id']])) { ?>
                      <span class="error"><?php echo $error_text[$language['language_id']]; ?></span>
                  <?php } ?>
                </td>
                </tr>
              </table>
            </div>
    		<?php } ?>
          </td>
        </tr>

        <tr>
          <td><?php echo $entry_no_display_days; ?></td>
          <td>
              <?php foreach ($no_display_days as $code => $name) { ?>
                <?php if (in_array($code, $deliverydate_no_display_day)) { ?>
                <input type="checkbox" name="deliverydate_no_display_day[]" value="<?php echo $code; ?>" checked="checked" />
                <?php echo $name; ?>
                <?php } else { ?>
                <input type="checkbox" name="deliverydate_no_display_day[]" value="<?php echo $code; ?>" />&nbsp;
                <?php echo $name; ?>
                <?php } ?>&nbsp;&nbsp;
              <?php } ?>
          </td>
        </tr>
      </table>
      <br />
      <table id="special_day" class="list">
        <thead>
          <tr>
            <td class="left"><?php echo $entry_special_day; ?></td>
            <td></td>
          </tr>
        </thead>
        <?php if ($special_days) { ?>
        <?php $special_day_row = 0; ?>
        <?php foreach ($special_days as $special_day) { ;?>
        <tbody id="special_day_row<?php echo $special_day_row; ?>">
          <tr>
            <td class="left">
              <input type="text" name="special_day[<?php echo $special_day_row; ?>][fulldate]" value="<?php echo $special_day['fulldate']; ?>" size="12" class="deliverydate" />
            </td>
            <td class="left"><a onclick="$('#special_day_row<?php echo $special_day_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
          </tr>
        </tbody>
        <?php $special_day_row++; ?>
		<?php } ?>
        <?php } else { $special_day_row = 0; }?>
        <tfoot>
          <tr>
            <td colspan="1"></td>
            <td class="center" width="200"><a onclick="addSpecialDay();" class="button"><span><?php echo $button_add_special_day; ?></span></a></td>
          </tr>
        </tfoot>
      </table>
      <br />
      <table id="range_hour" class="list">
        <thead>
          <tr>
            <td class="left" colspan="4"><?php echo $entry_range_hour; ?></td>
            <td></td>
          </tr>
        </thead>
        <?php if ($range_hours) { ?>
        <?php $range_hour_row = 0; ?>
        <?php foreach ($range_hours as $range_hour) { ;?>
        <tbody id="range_hour_row<?php echo $range_hour_row; ?>">
          <tr>
            <td class="left"><?php echo $text_from; ?>
              <input type="text" name="range_hour[<?php echo $range_hour_row; ?>][from]" value="<?php echo $range_hour['from']; ?>" size="12" class="time" /> -
            </td>
            <td class="left"><?php echo $text_to; ?>
              <input type="text" name="range_hour[<?php echo $range_hour_row; ?>][to]" value="<?php echo $range_hour['to']; ?>" size="12" class="time" />
            </td>
            <input type="hidden" name="range_hour[<?php echo $range_hour_row; ?>][id]" value="<?php echo $range_hour_row; ?>" />
            <td class="left" colspan="4"><a onclick="$('#range_hour_row<?php echo $range_hour_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
          </tr>
        </tbody>
        <?php $range_hour_row++; ?>
		<?php } ?>
        <?php } else { $range_hour_row = 0; }?>
        <tfoot>
          <tr>
            <td colspan="4"></td>
            <td class="center" width="200"><a onclick="addRangeHour();" class="button"><span><?php echo $button_add_range_hour; ?></span></a></td>
          </tr>
        </tfoot>
      </table>
    </form>
  </div>
</div>

<script type="text/javascript"><!--

      if ($('#deliverydate_custom').attr('checked')) {
          $('#custom_delivery').show();
      } else {
          $('#custom_delivery').hide();
      }

  $('#deliverydate_custom').click(function() {
      $(this).is(':checked') ? $("#custom_delivery").show() : $("#custom_delivery").hide();
  });

--></script>

<script type="text/javascript"><!--
    $.tabs('#languages a');
//--></script>

<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>

<script type="text/javascript"><!--
var special_day_row = <?php echo $special_day_row; ?>;

function addSpecialDay() {
	html  = '<tbody id="special_day' + special_day_row + '">';
	html += '<tr>';
	html += '<td class="left"><input type="text" name="special_day[' + special_day_row + '][fulldate]" value="" size="12" class="deliverydate" /></td>';
	html += '<td class="left"><a onclick="$(\'#special_day_row' + special_day_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '</tr>';
	html += '</tbody>';

	$('#special_day > tfoot').before(html);

	$('#special_day' + special_day_row + ' .deliverydate').datepicker({dateFormat: 'mm-dd', changeMonth: true});

	special_day_row++;
}
//--></script>

<script type="text/javascript"><!--
var range_hour_row = <?php echo $range_hour_row; ?>;

function addRangeHour() {
	html  = '<tbody id="range_hour' + range_hour_row + '">';
	html += '<tr>';
	html += '<td class="left"><?php echo $text_from; ?> <input type="text" name="range_hour[' + range_hour_row + '][from]" value="" size="12" class="time" /> - </td>';
	html += '<td class="left"><?php echo $text_to; ?> <input type="text" name="range_hour[' + range_hour_row + '][to]" value="" size="12" class="time" /></td>';
	html += '<input type="hidden" name="range_hour[' + range_hour_row + '][id]" value="' + range_hour_row + '" />';
	html += '<td class="left" colspan="4"><a onclick="$(\'#range_hour_row' + range_hour_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
	html += '</tr>';
	html += '</tbody>';

	$('#range_hour > tfoot').before(html);

	$('#range_hour' + range_hour_row + ' .time').timepicker({timeFormat: 'hh:mm'});

	range_hour_row++;
}
//--></script>

<script type="text/javascript"><!--
$(document).ready(function() {
	$('.time').timepicker({timeFormat: 'hh:mm'});
	$('.deliverydate').datepicker({dateFormat: 'yy-mm-dd'});
});
//--></script>
<?php echo $footer; ?>