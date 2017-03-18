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
	#filter-box {
		background: #E4EEF7;
		border: 1px solid #C4CED7;
		padding: 5px;
		margin-bottom: 15px;
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
	</div>
	<div class="content">
		<table width="100%" id="filter-box">
			<tr>
				<td><?php echo $entry_question; ?>
					<select id="question">
						<?php for ($i = 1; $i < count($questions); $i++) { ?>
							<option value="<?php echo $i; ?>" <?php if ($filters['question'] == $i) echo 'selected="selected"'; ?>><?php echo (strlen($questions[$i]) > 50) ? substr($questions[$i], 0, 50) . '...' : $questions[$i]; ?></option>
						<?php } ?>
					</select>
				</td>
				<td><?php echo $entry_order_status; ?>
					<select id="order_status_id">
						<option value="0"><?php echo $text_all_statuses; ?></option>
						<?php foreach ($order_statuses as $os) { ?>
							<option value="<?php echo $os['order_status_id']; ?>" <?php if ($os['order_status_id'] == $filters['order_status_id']) echo 'selected="selected"'; ?>><?php echo $os['name']; ?></option>
						<?php } ?>
					</select>
				</td>
				<td><?php echo $entry_date_start; ?>
					<input type="text" id="date_start" value="<?php echo $filters['date_start']; ?>" size="12" />
				</td>
				<td rowspan="2" align="right"><a onclick="filter()" class="button"><span><?php echo $button_filter; ?></span></a></td>
			</tr>
			<tr>
				<td><?php echo $entry_combine_multilingual; ?>
					<select id="multilingual">
						<option value="0" <?php if (!$filters['multilingual']) echo 'selected="selected"'; ?> ><?php echo $text_no; ?></option>
						<option value="1" <?php if ($filters['multilingual']) echo 'selected="selected"'; ?> ><?php echo $text_yes; ?></option>
					</select>
				</td>
				<td><?php echo $entry_include_historical_data; ?>
					<select id="historical">
						<option value="0" <?php if (!$filters['historical']) echo 'selected="selected"'; ?> ><?php echo $text_no; ?></option>
						<option value="1" <?php if ($filters['historical']) echo 'selected="selected"'; ?> ><?php echo $text_yes; ?></option>
					</select>
				</td>
				<td><?php echo $entry_date_end; ?>&nbsp;
					<input type="text" id="date_end" value="<?php echo $filters['date_end']; ?>" size="12" />
				</td>
			</tr>
		</table>
		<table class="list">
		<thead>
			<tr>
				<td class="left"><?php echo $column_response; ?></td>
				<td class="right"><?php echo $column_customer_orders; ?></td>
				<td class="right"><?php echo $column_customer_sales; ?></td>
				<td class="right"><?php echo $column_guest_orders; ?></td>
				<td class="right"><?php echo $column_guest_sales; ?></td>
				<td class="right"><?php echo $column_total_orders; ?></td>
				<td class="right"><?php echo $column_total_sales; ?></td>
			</tr>
		</thead>
		<tbody>
			<?php if ($responses) { ?>
				<?php foreach ($responses as $text => $response) { ?>
					<tr>
						<td class="left"><?php echo $text; ?></td>
						<td class="right"><?php echo $response['customer_responses']; ?></td>
						<td class="right"><?php echo $this->currency->format($response['customer_sales'], $this->config->get('config_currency')); ?></td>
						<td class="right"><?php echo $response['guest_responses']; ?></td>
						<td class="right"><?php echo $this->currency->format($response['guest_sales'], $this->config->get('config_currency')); ?></td>
						<td class="right"><strong><?php echo $response['customer_responses'] + $response['guest_responses']; ?></strong></td>
						<td class="right"><strong><?php echo $this->currency->format($response['total_sales'], $this->config->get('config_currency')); ?></strong></td>
					</tr>
				<?php } ?>
			<?php } else { ?>
				<tr>
					<td class="center" colspan="7"><?php echo $text_no_results; ?></td>
				</tr>
			<?php } ?>
		</tbody>
		</table>
		<?php echo $copyright; ?>
	</div>
</div>
<?php if ($version < 150) { ?>
	<script type="text/javascript" src="view/javascript/jquery/ui/ui.datepicker.js"></script>
<?php } else { ?>
	</div>
<?php } ?>
<script type="text/javascript"><!--
	$(document).ready(function() {
		$('#date_start').datepicker({dateFormat: 'yy-mm-dd'});
		$('#date_end').datepicker({dateFormat: 'yy-mm-dd'});
	});
	
	function filter() {
		url = 'index.php?route=<?php echo $type; ?>/<?php echo $name; ?>';		
		$('#filter-box :input').each(function(){
			if ($(this).val()) {
				url += '&' + $(this).attr('id') + '=' + encodeURIComponent($(this).val());
			}
		});
		
		location = url + '&token=<?php echo $token; ?>';
	}
//--></script>
<?php echo $footer; ?>