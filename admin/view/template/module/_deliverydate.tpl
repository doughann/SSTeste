<?php
//==============================================================================
// Delivery Date PRO
// 
// Desarrollado por: Cofran
// E-mail: franco.iglesias@gmail.com
// Sitio Web: http://www.wachipato.com
// Support: http://www.wachipato.com/support
//==============================================================================
?>

<?php echo $header; ?>
  <style type="text/css">
    .ui-dialog {
      box-shadow: 0 3px 6px #999;
    }
    .help {
      font-style: italic;
      margin-top: 5px;
    }
    td[colspan] .help {
      margin-top: 0;
    }
    table.form > tbody > tr > td {
      border-bottom: 1px solid #CCCCCC;
    }
    .multiple-select {
      width: 200px;
    }
    .settings-header {
      border-top: 1px solid #DBDBDB;
      background: #E4E4E4;
      color: #f6f6f6;
      text-transform: uppercase;
      font-weight: bold;
          -moz-text-shadow: 1px 2px 1px #f6f6f6;
          -webkit-text-shadow: 1px 2px 1px #f6f6f6;
          text-shadow: 1px 2px 1px #f6f6f6;
    }
    .settings-header td {
      margin-right: 25px;
    }
    .scrollbox {
      height: 176px;
    }
    .scrollbox label:nth-child(even) div {
      background: #E4EEF7;
    }
    .ui-dialog {
      padding: 0;
      position: fixed;
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
<div class="box">
  <?php if ($version < 150) { ?><div class="left"></div><div class="right"></div><?php } ?>
  <div class="heading">
    <h1 style="padding: 10px 2px 0"><img src="view/image/<?php echo $type; ?>.png" alt="" style="vertical-align: middle" /> <?php echo $heading_title; ?></h1>
    <div class="buttons">
      <a onclick="save(true)" class="button"><span><?php echo $button_save; ?></span></a>
      <a onclick="location = '<?php echo $exit; ?>'" class="button"><span><?php echo $button_cancel; ?></span></a>
    </div>
  </div>
    <div class="content">
      <form action="" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr class="settings-header"><td colspan="2"><?php echo $heading_general; ?></td></tr>
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td>
              <select name="<?php echo $name; ?>_status">
                <option value="1" <?php if (!empty(${$name.'_status'})) echo 'selected="selected"'; ?>><?php echo $text_enabled; ?></option>
                <option value="0" <?php if (empty(${$name.'_status'})) echo 'selected="selected"'; ?>><?php echo $text_disabled; ?></option>
              </select>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_required; ?></td>
            <td>
              <select name="<?php echo $name; ?>_data[required]">
                <?php $required = (isset(${$name.'_data'}['required'])) ? ${$name.'_data'}['required'] : '0'; ?>
                <option value="1" <?php if ($required == '1') echo 'selected="selected"'; ?>><?php echo $text_yes; ?></option>
                <option value="0" <?php if ($required == '0') echo 'selected="selected"'; ?>><?php echo $text_no; ?></option>
              </select>
            </td>
          </tr>
          <tr>
            <td valign="top"><?php echo $entry_design_html; ?></td>
            <td>
              <div id="languages" class="htabs">
                <?php foreach ($languages as $language) { ?>
                <a href="#language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
                <?php } ?>
              </div>
              <?php foreach ($languages as $language) { ?>
              <div id="language<?php echo $language['language_id']; ?>">
                <table class="form">
                  <tr>
                    <td><textarea name="<?php echo $name; ?>_data[description][<?php echo $language['code']; ?>]" cols="80" rows="10"><?php echo (isset(${$name.'_data'}['description'][$language['code']])) ? ${$name.'_data'}['description'][$language['code']] : ''; ?></textarea>
                  </tr>
                </table>
              </div>
              <?php } ?>
            </td>
          </tr>
          <tr>
            <td><?php echo $entry_min_interval; ?></td>
            <td><input type="text" name="<?php echo $name; ?>_data[min_interval]" value="<?php echo (isset(${$name.'_data'}['min_interval'])) ? ${$name.'_data'}['min_interval'] : '0'; ?>" /></td>
          </tr>
          <tr>
            <td><?php echo $entry_max_interval; ?></td>
            <td><input type="text" name="<?php echo $name; ?>_data[max_interval]" value="<?php echo (isset(${$name.'_data'}['max_interval'])) ? ${$name.'_data'}['max_interval'] : '90'; ?>" /></td>
          </tr>

          <tr>
            <td valign="top"><?php echo $entry_no_display_days; ?></td>
            <td><div class="scrollbox">
                <?php foreach ($this->getDisplayDays() as $key => $value) { ?>
                <?php $days = (isset(${$name.'_data'}['display_days']) ? ${$name.'_data'}['display_days'] : array()); ?>
                <label><div>
                  <?php if (in_array($key, $days)) { ?>
                  <input type="checkbox" name="<?php echo $name; ?>_data[display_days][]" value="<?php echo $key; ?>" checked="checked" />
                  <?php echo $value; ?>
                  <?php } else { ?>
                  <input type="checkbox" name="<?php echo $name; ?>_data[display_days][]" value="<?php echo $key; ?>" />
                  <?php echo $value; ?>
                  <?php } ?>
                </div></label>
                <?php } ?>
              </div>
            </td>
          </tr>



          <tr>
            <td><?php echo $entry_time_selection; ?></td>
            <td>
              <select name="<?php echo $name; ?>_data[time_selection]">
                <?php $time_selection = (isset(${$name.'_data'}['time_selection'])) ? ${$name.'_data'}['time_selection'] : '0'; ?>
                <option value="1" <?php if ($time_selection == '1') echo 'selected="selected"'; ?>><?php echo $text_yes; ?></option>
                <option value="0" <?php if ($time_selection == '0') echo 'selected="selected"'; ?>><?php echo $text_no; ?></option>
              </select>
            </td>
          </tr>
          <tr id="view_range_hour" style="display:none;">
            <td valign="top"><?php echo $entry_ranges_hours; ?></td>
            <td>
             <table id="range_hour" class="list">
                <thead>
                  <tr>
                    <td class="left" colspan="2"><?php echo $entry_range_hour; ?></td>
                    <td></td>
                  </tr>
                </thead>
                <?php if (isset(${$name.'_data'}['ranges_hours'])) { ?>
                <?php $range_hour_row = 0; ?>
                <?php foreach (${$name.'_data'}['ranges_hours'] as $range_hour) { ?>
                <tbody id="range_hour_row<?php echo $range_hour_row; ?>">
                  <tr>
                    <td class="left"><?php echo $text_from; ?>
                      <input type="text" name="<?php echo $name; ?>_data[ranges_hours][<?php echo $range_hour_row; ?>][from]" value="<?php echo $range_hour['from']; ?>" size="12" class="time" />
                    </td>
                    <td class="left"><?php echo $text_to; ?>
                      <input type="text" name="<?php echo $name; ?>_data[ranges_hours][<?php echo $range_hour_row; ?>][to]" value="<?php echo $range_hour['to']; ?>" size="12" class="time" />
                    </td>
                    <input type="hidden" name="<?php echo $name; ?>_data[ranges_hours][<?php echo $range_hour_row; ?>][id]" value="<?php echo $range_hour['id']; ?>" />
                    <td class="left"><a onclick="$('#range_hour_row<?php echo $range_hour_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
                  </tr>
                </tbody>
                <?php $range_hour_row++; ?>
                <?php } ?>
                <?php } else { $range_hour_row = 0; }?>
                <tfoot>
                  <tr>
                    <td colspan="2"></td>
                    <td class="center" width="200"><a onclick="addRangeHour();" class="button"><span><?php echo $button_add_range_hour; ?></span></a></td>
                  </tr>
                </tfoot>
              </table>
            </td>
          </tr>


<?php /*
          <tr>
            <td valign="top"><?php echo $entry_delivery_off; ?></td>
            <td>
             <table id="special_day" class="list">
                <thead>
                  <tr>
                    <td class="left"><?php echo $entry_period_type; ?></td>
                    <td class="left"><?php echo $entry_period_day; ?></td>
                    <td class="left"><?php echo $text_action; ?></td>
                  </tr>
                </thead>
                <?php if (isset(${$name.'_data'}['specials_days'])) { ?>
                <?php $special_day_row = 0; ?>
                <?php $sd_options = array('*' => $text_select,'single' => $text_single,'recurrent' => $text_recurrent,'period' => $text_period); ?>
                <?php foreach (${$name.'_data'}['specials_days'] as $special_day) { ?>
                <tbody id="special_day_row<?php echo $special_day_row; ?>">
                  <tr>
                    <td class="left"><?php echo $text_from; ?>
                      <select name="<?php echo $name; ?>_data[specials_days][<?php echo $special_day_row; ?>][type]" onchange="rules('<?php echo $special_day_row; ?>')" >
                        <?php foreach ($sd_options as $option => $text) { ?>
                          <option value="<?php echo $option; ?>" <?php echo ($special_day['type'] == $option) ? 'selected="selected"' : ''; ?>><?php echo $text; ?></option>
                        <?php } ?>
                      </select>
                    </td>
                    <td class="left">
                      <?php if ($special_day['type'] == 'single') { ?>
                      <?php echo $special_day['type']; ?><input type="text" name="<?php echo $name; ?>_data[specials_days][<?php echo $special_day_row; ?>][single_date]" value="<?php echo $special_day['single_date']; ?>" class="date" />
                      <?php } ?>

                      <?php if ($special_day['type'] == 'recurrent') { ?>
                      <?php echo $special_day['type']; ?><input type="text" name="<?php echo $name; ?>_data[specials_days][<?php echo $special_day_row; ?>][recurrent_date]" value="<?php echo $special_day['recurrent_date']; ?>"class="date" />
                      <?php } ?>

                      <?php if ($special_day['type'] == 'period') { ?>
                      <?php echo $special_day['type']; ?><input type="text" name="<?php echo $name; ?>_data[specials_days][<?php echo $special_day_row; ?>][period_start]" value="<?php echo $special_day['period_start']; ?>" class="period_start" />
                      <input type="text" name="<?php echo $name; ?>_data[specials_days][<?php echo $special_day_row; ?>][period_end]" value="<?php echo $special_day['period_end']; ?>" class="period_end" />
                      <?php } ?>

                    </td>
                    <td class="left"><a onclick="$('#special_day_row<?php echo $special_day_row; ?>').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>
                  </tr>
                </tbody>
                <?php $special_day_row++; ?>
                <?php } ?>
                <?php } else { $special_day_row = 0; }?>
                <tfoot>
                  <tr>
                    <td colspan="2"></td>
                    <td class="center" width="200"><a onclick="addSpecialsDays();" class="button"><span><?php echo $button_add_special_day; ?></span></a></td>
                  </tr>
                </tfoot>
              </table>
            </td>
          </tr>
*/ ?>

          <tr>
            <td valign="top"><?php echo $entry_delivery_off; ?></td>
            <td>
             <table id="special_day" class="list">
                <thead>
                  <tr>
                    <td class="left"><?php echo $entry_period_type; ?></td>
                    <td class="left"><?php echo $entry_period_day; ?></td>
                    <td class="left"><?php echo $text_action; ?></td>
                  </tr>
                </thead>
                <?php $specials_days = (isset(${$name.'_data'}['specials_days']) ? ${$name.'_data'}['specials_days'] : array()); ?>
                <?php $special_day_row = 0; ?>
                <?php $sd_options = array('*' => $text_select,'single' => $text_single,'recurrent' => $text_recurrent,'period' => $text_period); ?>
                <?php foreach ($specials_days as $special_day) { ?>
                <tbody id="special_day_row<?php echo $special_day_row; ?>">
                  <tr>
                    <td class="left">
                            <select name="<?php echo $name; ?>_data[specials_days][<?php echo $special_day_row; ?>][type]" id="type<?php echo $special_day_row; ?>" onchange="$('#field<?php echo $special_day_row; ?>').load('index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/special_day&token=<?php echo $token; ?>&type=' + this.value + '&id=<?php echo $special_day_row; ?>');">
                              <?php foreach ($sd_options as $option => $text) { ?>
                                <option value="<?php echo $option; ?>" <?php echo ($special_day['type'] == $option) ? 'selected="selected"' : ''; ?>><?php echo $text; ?></option>
                              <?php } ?>
                            </select>
                    </td>
                    <td class="left" id="field<?php echo $special_day_row; ?>"></td>
                    <td class="left"><a onclick="$('#special_day_row<?php echo $special_day_row; ?>').remove();" class="button"><?php echo $button_remove; ?></a></td>
                  </tr>
                </tbody>
                      <?php $special_day_row++; ?>
                      <?php } ?>
                <tfoot>
                  <tr>
                    <td colspan="2"></td>
                    <td class="left"><a onclick="addSpecialsDays();" class="button"><?php echo $button_add_special_day; ?></a></td>
                  </tr>
                </tfoot>
              </table>
            </td>
          </tr>


        </table>
      </div><!-- END:CONTENT -->
      </form>
    </div>
  </div>

<?php if ($version > 150) { ?>
  </div>
<?php } ?>

<script type="text/javascript">
/*
$(document).ready(function() {
  $(document).on('focus',"input.date", function(){
    $(this).datepicker({dateFormat: 'yy-mm-dd'});
  });
});

$(window).load(function() {
  $(document).on('focus',"input.period_start, input.period_end", function(){
    $('#special_day').find('#period_date').each(function() {
      var startPeriod = $(this).find('input.period_start' );
      var endPeriod = $(this).find('input.period_end' );
      startPeriod.datepicker({
        dateFormat: 'yy-mm-dd',
        onClose: function(dateText, inst) {
          if (endPeriod.val() != '') {
            var startDate = startPeriod.datetimepicker('getDate');
            var endDate = endPeriod.datetimepicker('getDate');
            if (startDate > endDate)
              endPeriod.datetimepicker('setDate', startDate);
          }
          else {
            endPeriod.val(dateText);
          }
        },
        onSelect: function (selectedDateTime){
          endPeriod.datepicker('option', 'minDate', startPeriod.datetimepicker('getDate') );
        }
      });
      endPeriod.datepicker({
        dateFormat: 'yy-mm-dd',
        onClose: function(dateText, inst) {
          if (startPeriod.val() != '') {
            var startDate = startPeriod.datetimepicker('getDate');
            var endDate = endPeriod.datetimepicker('getDate');
            if (startDate > endDate)
              startPeriod.datetimepicker('setDate', endDate);
          }
          else {
            startPeriod.val(dateText);
          }
        },
        onSelect: function (selectedDateTime){
          startPeriod.datepicker('option', 'maxDate', endPeriod.datetimepicker('getDate') );
        }
      });
    });
  });
});
*/



</script>

<script type="text/javascript"><!--
$('#field-id').load('index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/special_day&token=<?php echo $token; ?>&type=' + $('#type-id').attr('value') + '&id=0');
//--></script>

<?php $special_day_row = 0; ?>
<?php foreach ($specials_days as $special_day) { ?>
<script type="text/javascript"><!--
$('#field<?php echo $special_day_row; ?>').load('index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/special_day&token=<?php echo $token; ?>&type=<?php echo $special_day['type']; ?>&id=<?php echo $special_day_row; ?>');
//--></script>
<?php $special_day_row++; ?>
<?php } ?>

<script type="text/javascript"><!--
var special_day_row = <?php echo $special_day_row; ?>;

function addSpecialsDays() {
  html  = '<tbody id="special_day_row' + special_day_row + '">';
  html += '<tr>';
  html += '<td class="left"><select name="<?php echo $name; ?>_data[specials_days][' + special_day_row + '][type]" id="type' + special_day_row + '" onchange="$(\'#field' + special_day_row + '\').load(\'index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/special_day&token=<?php echo $token; ?>&type=\' + this.value + \'&id=' + special_day_row + '\');">';
  <?php foreach ($sd_options as $option => $text) { ?>
  html += '<option value="<?php echo $option; ?>"><?php echo $text; ?></option>';
  <?php } ?>
  html += '</select></td>';
  html += '<td class="left" id="field' + special_day_row + '"></td>';
  html += '<td class="left"><a onclick="$(\'#special_day_row' + special_day_row + '\').remove();" class="button"><?php echo $button_remove; ?></a></td>';
  html += '</tr>';
  html += '</tbody>';

  $('#special_day > tfoot').before(html);

  $('#special_day_row' + special_day_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});

  $('#field' + special_day_row).load('index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/special_day&token=<?php echo $token; ?>&type=' + $('#type' + special_day_row).attr('value') + '&id=' + special_day_row);
  
  special_day_row++;
}
//--></script> 




<script type="text/javascript"><!--
  $('select[name=\'<?php echo $name; ?>_data[time_selection]\']').bind('change', function() {
    if (this.value == '1') {
      $('#view_range_hour').show();
    } else {
      $('#view_range_hour').hide();
    }
  });

  $('select[name=\'<?php echo $name; ?>_data[time_selection]\']').trigger('change');

  function save(exit) {
    $('<div />').dialog({
      title: '<?php echo $text_saving; ?>',
      closeOnEscape: false,
      draggable: false,
      modal: true,
      resizable: false,
      open: function(event, ui) {
        $('.ui-dialog-content').hide();
        $('.ui-dialog-titlebar-close').hide();
      }
    }).dialog('open');
    
    $.ajax({
      type: 'POST',
      url: 'index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/save&token=<?php echo $token; ?>',
      data: $('#form input:checked, #form input[type="hidden"], #form input[type="text"], #form select, #form textarea'),
      success: function(error) {
        if (error) exit = false;
        $('.ui-dialog-titlebar').css(error ? {'background': '#C00', 'border': '1px solid #A00'} : {'background': '#0C0', 'border': '1px solid #0A0'});
        $('.ui-dialog-content').dialog('option', 'title', error ? error : '<?php echo $text_saved; ?>');
        setTimeout(function(){ $('.ui-dialog-content').dialog('close'); if (exit) location = '<?php echo html_entity_decode($exit, ENT_QUOTES, 'UTF-8'); ?>'; }, error ? 2000 : 1000);
      }
    });
  }

  var range_hour_row = <?php echo $range_hour_row; ?>;

  function addRangeHour() {
    html  = '<tbody id="range_hour_row' + range_hour_row + '">';
    html += '<tr>';
    html += '<td class="left"><?php echo $text_from; ?> <input type="text" name="<?php echo $name; ?>_data[ranges_hours][' + range_hour_row + '][from]" value="" size="12" class="time" /></td>';
    html += '<td class="left"><?php echo $text_to; ?> <input type="text" name="<?php echo $name; ?>_data[ranges_hours][' + range_hour_row + '][to]" value="" size="12" class="time" /></td>';
    html += '<input type="hidden" name="<?php echo $name; ?>_data[ranges_hours][' + range_hour_row + '][id]" value="' + range_hour_row + '" />';
    html += '<td class="left" colspan="4"><a onclick="$(\'#range_hour_row' + range_hour_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
    html += '</tr>';
    html += '</tbody>';

    $('#range_hour > tfoot').before(html);

    $('#range_hour_row' + range_hour_row + ' .time').timepicker({timeFormat: 'hh:mm'});

    range_hour_row++;
  }

<?php /*
  function rules(id)
  {
    var value = $('select[name=\'<?php echo $name; ?>_data[specials_days][' + id + '][type]\']').val();
    $('#special_day_row' + id + ' .type').hide();
    $('#type-' + value + '-' + id).show();
  }

  var special_day_row = <?php echo $special_day_row; ?>;

  function addSpecialsDays() {
    html  = '<tbody id="special_day_row' + special_day_row + '">';
    html += '<tr>';
    html += '<td class="left"><select name="<?php echo $name; ?>_data[specials_days][' + special_day_row + '][type]" onchange="rules(' + special_day_row + ')" ><option value="select" selected="selected">-- Select Option --</option><option value="single">Single Day</option><option value="recurrent">Recurrent Day</option><option value="period">Period Day</option></select></td>';
    html += '<td class="center type" id="type-select-' + special_day_row + '">Please select option rule.</td>';

    //single//
    html += '<td class="left type" id="type-single-' + special_day_row + '" style="display:none;"><input type="text" name="<?php echo $name; ?>_data[specials_days][' + special_day_row + '][single_date]" value="" class="date" /></td>';

    //recurrent//
    html += '<td class="left type" id="type-recurrent-' + special_day_row + '" style="display:none;"><input type="text" name="<?php echo $name; ?>_data[specials_days][' + special_day_row + '][recurrent_date]" value="" class="date" /></td>';

    //period//
    html += '<td class="left type" id="type-period-' + special_day_row + '" style="display:none;"><input type="text" name="<?php echo $name; ?>_data[specials_days][' + special_day_row + '][period_start]" class="period_start" value="" /> <input type="text" name="<?php echo $name; ?>_data[specials_days][' + special_day_row + '][period_end]" class="period_end" value="" /></td>';

    html += '<td class="left" colspan="4"><a onclick="$(\'#special_day_row' + special_day_row + '\').remove();" class="button"><span><?php echo $button_remove; ?></span></a></td>';
    html += '</tr>';
    html += '</tbody>';

    $('#special_day > tfoot').before(html);

    //$('#range_hour_row' + special_day_row + ' .time').timepicker({timeFormat: 'hh:mm'});
    $('#special_day_row' + special_day_row + ' .date').datepicker({dateFormat: 'yy-mm-dd'});

    var startPeriod = $('#special_day_row' + special_day_row + ' .period_start');
    var endPeriod = $('#special_day_row' + special_day_row + ' .period_end');

    startPeriod.datepicker({ 
      dateFormat: 'yy-mm-dd',
      onClose: function(dateText, inst) {
        if (endPeriod.val() != '') {
          var startDate = startPeriod.datetimepicker('getDate');
          var endDate = endPeriod.datetimepicker('getDate');
          if (startDate > endDate)
            endPeriod.datetimepicker('setDate', startDate);
        }
        else {
          endPeriod.val(dateText);
        }
      },
      onSelect: function (selectedDateTime){
        endPeriod.datepicker('option', 'minDate', startPeriod.datetimepicker('getDate') );
      }
    });
    endPeriod.datepicker({ 
      dateFormat: 'yy-mm-dd',
      onClose: function(dateText, inst) {
        if (startPeriod.val() != '') {
          var startDate = startPeriod.datetimepicker('getDate');
          var endDate = endPeriod.datetimepicker('getDate');
          if (startDate > endDate)
            startPeriod.datetimepicker('setDate', endDate);
        }
        else {
          startPeriod.val(dateText);
        }
      },
      onSelect: function (selectedDateTime){
        startPeriod.datepicker('option', 'maxDate', endPeriod.datetimepicker('getDate') );
      }
    });

    special_day_row++;
  }
*/?>
//--></script>

<script type="text/javascript" src="view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script> 
<script type="text/javascript"><!--

/*
  function periodDay(field) {

alert(field);

  var startPeriod = $('.period_start');
  var endPeriod = $('.period_end');
    startPeriod.datepicker({
      dateFormat: 'yy-mm-dd',
      onClose: function(dateText, inst) {
        if (endPeriod.val() != '') {
          var startDate = startPeriod.datetimepicker('getDate');
          var endDate = endPeriod.datetimepicker('getDate');
          if (startDate > endDate)
            endPeriod.datetimepicker('setDate', startDate);
        }
        else {
          endPeriod.val(dateText);
        }
      },
      onSelect: function (selectedDateTime){
        endPeriod.datepicker('option', 'minDate', startPeriod.datetimepicker('getDate') );
      }
    });
    endPeriod.datepicker({
      dateFormat: 'yy-mm-dd',
      onClose: function(dateText, inst) {
        if (startPeriod.val() != '') {
          var startDate = startPeriod.datetimepicker('getDate');
          var endDate = endPeriod.datetimepicker('getDate');
          if (startDate > endDate)
            startPeriod.datetimepicker('setDate', endDate);
        }
        else {
          startPeriod.val(dateText);
        }
      },
      onSelect: function (selectedDateTime){
        startPeriod.datepicker('option', 'maxDate', endPeriod.datetimepicker('getDate') );
      }
    });
  }
*/

  $('.date').datepicker({dateFormat: 'yy-mm-dd'});
  $('.datetime').datetimepicker({
    dateFormat: 'yy-mm-dd',
    timeFormat: 'h:m'
  });
  $('.time').timepicker({timeFormat: 'hh:mm'});

  $('#tabs a').tabs(); 
  $('#languages a').tabs();
//--></script> 
<?php echo $footer; ?>