<?php
//==============================================================================
// Checkout Survey v155.1
//
// Author: Clear Thinking, LLC
// E-mail: johnathan@getclearthinking.com
// Website: http://www.getclearthinking.com
//==============================================================================
?>

<?php
$type = 'total';
$name = 'checkout_survey';

$config_url = explode('/', HTTP_SERVER);
$first_time = true;
if ($this->config->get($name . '_first_time') && $this->customer->isLogged()) {
	$first_time_query = $this->db->query("SELECT * FROM `" . DB_PREFIX . "order` WHERE customer_id = " . (int)$this->customer->getId() . " AND order_status_id > 0");
	$first_time = ($first_time_query->num_rows == 0);
}

if ($config_url[2] != $this->request->server['HTTP_HOST']) {
	$this->log->write('Checkout Survey Error: Your site is declared as ' . $config_url[2] . ' but is being browsed as ' . $this->request->server['HTTP_HOST'] . '. Any extension utilizing AJAX, including the Checkout Survey, will not work unless the customer is browsing the version declared in the config.php file. You should redirect the other version to the config.php version using your .htaccess file.');
	echo '
		<div id="<?php echo $name; ?>" class="box">
			<div class="box-heading">Checkout Survey</div>
			<div class="box-content">Error: Please see the admin error log.</div>
		</div>
	';
} elseif ($this->config->get($name . '_status') && $first_time) {
	$this->load->model($type . '/' . $name);
	$data = $this->{'model_'.$type.'_'.$name}->getSettings();
	$language = $this->session->data['language'];
	$version = (!defined('VERSION')) ? 140 : (int)substr(str_replace('.', '', VERSION), 0, 3);
?>

<style type="text/css">
	#<?php echo $name; ?> {
		line-height: 20px;
	}
	#<?php echo $name; ?> input[type="checkbox"], #<?php echo $name; ?> input[type="radio"] {
		cursor: pointer;
	}
	#<?php echo $name; ?> input[type="text"] {
		margin: 0;
	}
	.question-block {
		display: inline-block;
		padding: 10px 15px;
		vertical-align: top;
	}
	.required-question {
		color: #F00;
	}
	.question-name {
		font-weight: bold;
	}
	.question-group {
		font-style: italic;
	}
	.question-block input[type="text"] {
		width: 150px;
	}
</style>
<div id="<?php echo $name; ?>" class="box">
	<div class="box-heading"><?php echo $data['heading'][$language]; ?></div>
	<div class="box-content">
		<?php echo html_entity_decode($data['text'][$language], ENT_QUOTES, 'UTF-8'); ?>
		<br />
		<?php for ($i = 0; $i < count($data['type']); $i++) { ?>
			<?php if (empty($data['question'][$language][$i])) continue; ?>
			<div id="<?php echo $name . '_' . $i; ?>" class="question-block">
				<?php if ($data['required'][$i]) { ?>
					<span class="required-question">*</span>
				<?php } ?>
				<span class="question-name"><?php echo html_entity_decode($data['question'][$language][$i], ENT_QUOTES, 'UTF-8'); ?></span>
				<br />
				
				<?php $responses = array_map('trim', explode(';', $data['responses'][$language][$i])); ?>
				<?php $set_responses = (isset($this->session->data[$name . '_' . $i])) ? explode('; ', $this->session->data[$name . '_' . $i]) : array(); ?>
				
				<?php if ($data['type'][$i] == 'radio' || $data['type'][$i] == 'checkbox') { ?>
					
					<?php foreach ($responses as $response) { ?>
						<?php $checked = (in_array($response, $set_responses)) ? 'checked="checked"' : ''; ?>
						<?php if (strpos($response, '[') === 0) { ?>
							<span class="question-group"><?php echo str_replace(array('[', ']'), '', $response); ?></span><br />
						<?php } else { ?>
							<label><input type="<?php echo $data['type'][$i]; ?>" name="<?php echo $name . '_' . $i; ?>" value="<?php echo $response; ?>" onclick="setResponse()" <?php echo $checked; ?> /> <?php echo $response; ?></label><br />
						<?php } ?>
					<?php } ?>
					
					<?php if ($data['other'][$language][$i]) { ?>
						<?php $other_values = implode('; ', array_diff($set_responses, $responses)); ?>
						<label><input type="<?php echo $data['type'][$i]; ?>" name="<?php echo $name . '_' . $i; ?>" value="<?php echo $other_values; ?>" onclick="setResponse()" <?php if ($other_values) echo 'checked="checked"'; ?> /> <?php echo $data['other'][$language][$i]; ?></label>
						<input type="text" value="<?php echo $other_values; ?>" onblur="setResponse()" onkeyup="$(this).prev().find('input').val($(this).val())" />
					<?php } ?>
					
				<?php } elseif ($data['type'][$i] == 'select' || $data['type'][$i] == 'multi') { ?>
					
					<?php if ($data['type'][$i] == 'select') { ?>
						<select name="<?php echo $name . '_' . $i; ?>" onchange="selectActions($(this)); setResponse()">
							<option value="" class="please-select"><?php echo $this->language->get('text_select'); ?></option>
					<?php } elseif ($data['type'][$i] == 'multi') { ?>
						<select name="<?php echo $name . '_' . $i; ?>" onchange="setResponse()" multiple="multiple" size="<?php echo $data['multiselect_size']; ?>">
					<?php } ?>
					
					<?php $grouped = false; ?>
					
					<?php foreach ($responses as $response) { ?>
						<?php if (strpos($response, '[') === 0) { ?>
							<?php if ($grouped) { ?>
								</optgroup>
							<?php } else { ?>
								<?php $grouped = true; ?>
							<?php } ?>
							<optgroup label="<?php echo str_replace(array('[', ']'), '', $response); ?>">
						<?php } else { ?>
							<?php $selected = (in_array($response, $set_responses)) ? 'selected="selected"' : ''; ?>
							<option value="<?php echo $response; ?>" <?php echo $selected; ?>><?php echo $response; ?></option>
						<?php } ?>
					<?php } ?>
					
					<?php if ($data['other'][$language][$i]) { ?>
						<?php $selected = (!empty($this->session->data[$name . '_' . $i]) && array_diff($set_responses, $responses)) ? 'selected="selected"' : ''; ?>
						<option class="other-response" value="<?php echo implode('; ', array_diff($set_responses, $responses)); ?>" <?php echo $selected; ?>><?php echo $data['other'][$language][$i]; ?></option>
					<?php } ?>
					
					<?php if ($grouped) { ?>
						</optgroup>
					<?php } ?>
					
					</select>
					
					<?php if ($data['other'][$language][$i]) { ?>
						<br />
						<?php $show_or_hide = ($selected) ? 'value="' . implode('; ', array_diff($set_responses, $responses)) . '"' : 'style="display: none"'; ?>
						<input type="text" onblur="setResponse()" onkeyup="$(this).parent().find('.other-response').val($(this).val())" <?php echo $show_or_hide; ?> />
					<?php } ?>
					
				<?php } else { ?>
					
					<?php if ($data['type'][$i] == 'textarea') { ?>
						<textarea  onblur="setResponse()" name="<?php echo $name . '_' . $i; ?>"><?php echo ($set_responses) ? implode('; ', $set_responses) : $data['responses'][$language][$i]; ?></textarea>
					<?php } else { ?>
						<input type="text" onblur="setResponse()" name="<?php echo $name . '_' . $i; ?>" class="<?php echo $data['type'][$i]; ?>" value="<?php echo ($set_responses) ? implode('; ', $set_responses) : $data['responses'][$language][$i]; ?>" />
					<?php } ?>
					
				<?php } ?>
			</div>
		<?php } ?>
	</div>
</div>

<?php if ($version < 150) { ?>
	<script type="text/javascript" src="//ajax.googleapis.com/ajax/libs/jqueryui/1.10.1/jquery-ui.min.js"></script>
	<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.min.js"></script>
<?php } elseif (!isset($this->request->get['route']) || $this->request->get['route'] != 'product/product') { ?>
	<script type="text/javascript" src="catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.min.js"></script>
<?php } ?>

<script type="text/javascript"><!--
	$(document).ready(function(){
		setResponse();
		
		$('.question-block .date').datepicker({
			dateFormat: 'dd-mm-yy', minDate:1, beforeShowDay: $.datepicker.noWeekends
		});	
		$('.question-block .time').timepicker({
			timeFormat: 'h:mm tt',
			ampm: true
		});
		$('.question-block .datetime').datetimepicker({
			dateFormat: 'yy-mm-dd',
			timeFormat: 'h:mm tt',
			ampm: true,
			separator: ' @ '
		});
	});
	
	function selectActions(element) {
		if (element.parent().find('.other-response:selected').length) {
			element.parent().find('input').show();
		} else {
			element.parent().find('input').hide();
		}
	}
	
	function setResponse() {
		<?php
		$required = array();
		for ($r = 0; $r < count($data['required']); $r++) {
			if ($data['required'][$r]) {
				$getval = array();
				$getval[] = '$("input[type=checkbox][name=' . $name . '_' . $r . ']").is(":checked")';
				$getval[] = '$("input[type=radio][name=' . $name . '_' . $r . ']").is(":checked")';
				$getval[] = '$("input[type=text][name=' . $name . '_' . $r . ']").val()';
				$getval[] = '$("select[name=' . $name . '_' . $r . ']").val()';
				$getval[] = '$("textarea[name=' . $name . '_' . $r . ']").val()';
				$required[] = '(' . implode(' || ', $getval) . ')';
			}
		}
		?>
		
		<?php if ($required) { ?>
			if (<?php echo implode(' && ', $required); ?>) {
				$('.disabled-button').remove();
				$('<?php echo $data['button']; ?>').show();
			} else {
				$('<?php echo $data['button']; ?>').each(function(){
					if ($(this).prop('tagName').toLowerCase() == 'input') {
						var tag = '<input type="' + $(this).attr('type') + '" value="' + $(this).attr('value') + '" />';
					} else {
						var tag = '<' + $(this).prop('tagName') + '>' + $(this).html() + '</' + $(this).prop('tagName') + '>';
					}
					$(this).before($(tag).css(getStyles($(this))).addClass('disabled-button').click(function(){ alert('<?php echo $data['error'][$language]; ?>'); })).hide();
				});
			}
		<?php } ?>
		
		$.ajax({
			type: 'POST',
			url: 'index.php?route=<?php echo $type; ?>/<?php echo $name; ?>/setResponse',
			data: {count: <?php echo $i; ?>, responses: $('#<?php echo $name; ?> input:checked, #<?php echo $name; ?> input[type="text"], #<?php echo $name; ?> select, #<?php echo $name; ?> textarea').serializeArray()}
		});
	}
	
	function getStyles(element) {
		var dom = element.get(0);
		var style;
		var returns = {};
		if (window.getComputedStyle) {
			var camelize = function(a,b) {
				return b.toUpperCase();
			}
			style = window.getComputedStyle(dom, null);
			for (var i = 0; i < style.length; i++) {
				var prop = style[i];
				var camel = prop.replace(/\-([a-z])/g, camelize);
				var val = style.getPropertyValue(prop);
				returns[camel] = val;
			}
			return returns;
		}
		if (dom.currentStyle) {
			style = dom.currentStyle;
			for (var prop in style) {
				returns[prop] = style[prop];
			}
			return returns;
		}
		return element.css();
	}
//--></script>
	
<?php } ?>