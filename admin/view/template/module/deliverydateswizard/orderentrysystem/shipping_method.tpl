<div id="ddw_widget">
	<?php if ($ddw_order != false) : ?>
		<input type="text" name="DDW_date" value="<?php echo $ddw_order->ddw_delivery_date; ?>" style="display:none;" />
	<?php else: ?>
		<input type="text" name="DDW_date" value="" style="display:none;" />
	<?php endif; ?>
	
	<?php foreach($ddw_texts['textselect'] as $key=>$text) : ?>
		<div class="ddw_texts-<?php echo $key;?> ddw_texts" style="display:none;"><?php echo $text; ?></div>
	<?php endforeach; ?>
	
	<div class="right" style="text-align:right; width:auto;">
		<div id="ddw_calendar" style="float:right;"></div>
		<div style="clear:both"></div>
		
		<strong>Selected date:</strong>
		<span id="DDW_text">
			<?php if ($ddw_order != false) : ?>
				<?php echo date('Y-m-d', strtotime($ddw_order->ddw_delivery_date)); ?>
			<?php echo endif; ?>
		</span>
	</div>
	
	<div class="right delivery-times clear-after">
		<br />
		<b>
			Time Slots:<br />
			Enter manually:
			<?php if ($ddw_order != false) : ?>
				<input type="text" name="DDW_time_slot" value="<?php echo $ddw_order->ddw_time_slot; ?>" />
			<?php else: ?>
				<input type="text" name="DDW_time_slot" value="" />			
			<?php endif; ?>
		</b>
		<?php if (isset($ddw_delivery_times)) : ?>
			<?php foreach($ddw_delivery_times as $key=>$ddw_time_shipping) : ?>
				<div class="delivery-time-widget" data-shipping-method-code="<?php echo $key;?>">
					<?php foreach($ddw_time_shipping as $key=>$ddw_time) : ?>
						<div>
							<input type="radio" name="ddw_time_slot" checked="" class="ddw_time_slot" value="<?php echo strip_tags($ddw_time->text);?>" /><label><?php echo $ddw_time->text;?></label>
						</div>
					<?php endforeach; ?>
				</div>
			<?php endforeach; ?>
		<?php endif; ?>	
	</div>
	
	<div class="clear"></div>
	<script src="<?php echo $catalog_url;?>catalog/view/javascript/ddw.js"></script>
	<script>
		$(document).ready(function() {
			token = "<?php echo $token;?>";
			min_date = "<?php echo $min_date;?>";
			max_date = "<?php echo $max_date;?>";
			
			<?php if ($ddw_order != false) : ?>
				default_date = "<?php echo $ddw_order->ddw_delivery_date;?>";
				default_time = "<?php echo $ddw_order->ddw_time_slot;?>";
			<?php endif; ?>
			
			var ddw = new DDWFrontEnd(true);			
			
			
			$('#total_info').on('change', 'input[name="DDW_date"], input[name="ddw_time_slot"]', function() {
				console.log("date");				
				$.ajax({
					type: 'POST',
					url: "index.php?route=module/deliverydateswizard/order_entry_system_update&token=" + token,
					async: true,
					cache: false,
					data : {
						ddw_delivery_date : $("input[name='DDW_date']").val(),
						ddw_time_slot : $("input[name='ddw_time_slot']:checked").val()
					},
					//dataType : "json",
					complete: function(d) {
					},
					success: function(jsonData) {
						console.log(jsonData);
					}
				});				
			});
			
			
			$('#total_info').on('change', '#shipping', function() {
				//console.log("changed");
			});
			
			
		});
	</script>	
	
</div>	