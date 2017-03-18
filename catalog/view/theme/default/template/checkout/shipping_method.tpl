<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php if ($shipping_methods) { ?>
<p><?php echo $text_shipping_method; ?></p>
<table class="radio">
  <?php foreach ($shipping_methods as $shipping_method) { ?>
  <tr>
    <td colspan="3"><b><?php echo $shipping_method['title']; ?></b></td>
  </tr>
  <?php if (!$shipping_method['error']) { ?>
  <?php foreach ($shipping_method['quote'] as $quote) { ?>
  <tr class="highlight">
    <td><?php if ($quote['code'] == $code || !$code) { ?>
      <?php $code = $quote['code']; ?>
      <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" checked="checked" />
      <?php } else { ?>
      <input type="radio" name="shipping_method" value="<?php echo $quote['code']; ?>" id="<?php echo $quote['code']; ?>" />
      <?php } ?></td>
    <td><label for="<?php echo $quote['code']; ?>"><?php echo $quote['title']; ?></label></td>
    <td><?php if ($quote['code'] == 'okazaki.okazaki') { ?>
        Data da Entrega:<input type="text" name="okazaki" id="okazaki" style="text-align:center;"
        value=""  />
		<?php } ?>
		<?php if ($quote['code'] == 'anjochiryu.anjochiryu') { ?>
        Data da Entrega:<input type="text" name="anjochiryu" id="anjochiryu" style="text-align:center;"
        value=""  />
		<?php } ?>
		<?php if ($quote['code'] == 'toyota.toyota') { ?>
        Data da Entrega:<input type="text" name="toyota" id="toyota" style="text-align:center;"
        value=""  />
		<?php } ?>
		<?php if ($quote['code'] == 'nishiotakahamahekinan.nishiotakahamahekinan') { ?>
        Data da Entrega:<input type="text" name="nishiotakahamahekinan" id="nishiotakahamahekinan" style="text-align:center;"
        value=""  />
		<?php } ?>
	</td>
    <td style="text-align: right;"><label for="<?php echo $quote['code']; ?>"><?php echo $quote['text']; ?></label></td>
  </tr>
  <?php } ?>
  <?php } else { ?>
  <tr>
    <td colspan="3"><div class="error"><?php echo $shipping_method['error']; ?></div></td>
  </tr>
  <?php } ?>
  <?php } ?>
</table>
<br />
<?php } ?>
<b><?php echo $text_comments; ?></b>
<textarea name="comment" rows="8" style="width: 98%;"><?php echo $comment; ?></textarea>
<br />
<br />
<div class="buttons">
  <div class="right">
    <input type="button" value="<?php echo $button_continue; ?>" id="button-shipping-method" class="button" />
  </div>
</div>
<script type="text/javascript" src="catalog/view/javascript/jquery/ui/i18n/jquery.ui.datepicker-pt-BR.js"></script>
<script type="text/javascript"><!--
$(document).ready(function() {
	var sd = new Date();
    sd.setDate(sd.getDate() + 1); //move to the next day

	$('#okazaki').datepicker({dateFormat: 'D, dd M yy', minDate:1, beforeShowDay: $.datepicker. noWeekends});
	$('#okazaki').datepicker('setDate', sd); 
	
	$('#anjochiryu').datepicker({dateFormat: 'D, dd M yy', minDate:1, beforeShowDay: function(date){ return [date.getDay() == 2 || date.getDay() ==  5,""]}});
	$('#anjochiryu').datepicker('setDate', sd); 
	
	$('#toyota').datepicker({dateFormat: 'D, dd M yy', minDate:1, beforeShowDay: function(date){ return [date.getDay() == 3,""]}});
	$('#toyota').datepicker('setDate', sd); 
	
	$('#nishiotakahamahekinan').datepicker({dateFormat: 'D, dd M yy', minDate:1, beforeShowDay: function(date){ return [date.getDay() == 1 || date.getDay() ==  4,""]}});
	$('#nishiotakahamahekinan').datepicker('setDate', sd); 

});
//--></script>