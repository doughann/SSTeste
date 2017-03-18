<div id="ddw_widget">
	<input type="text" name="DDW_date" value="" style="display:none;" />
	<input type="text" name="DDW_time_slot" value="" style="display:none;" />
    <?php if (!empty($ddw_texts['textselect'])) : ?>
        <?php foreach($ddw_texts['textselect'] as $key=>$text) : ?>
            <div class="ddw_texts-<?php echo $key;?> ddw_texts" style="display:none;"><?php echo $text; ?></div>
        <?php endforeach; ?>
    <?php endif; ?>

	<div class="left" style="width:280px;">
		<div id="ddw_calendar"></div>
		<br />
        <?php if (!empty($ddw_texts['textselecteddate'])) : ?>
            <?php foreach($ddw_texts['textselecteddate'] as $key=>$text) : ?>
                <div class="ddw_texts-<?php echo $key;?> ddw_texts" style="display:none;"><?php echo $text; ?></div>
            <?php endforeach; ?>
        <?php endif; ?>
		<span id="DDW_text">
		</span><br />
		<br />
	</div>
	
	<div class="left delivery-times clear-after">
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
	
	<div style="clear:both;"></div>
</div>	


<script src="<?php echo $catalog_url;?>catalog/view/javascript/ddw.js"></script>
<script>
	$(document).ready(function() {
		token = "";
		min_date = "<?php echo $min_date;?>";
		max_date = "<?php echo $max_date;?>";
        required_error = '';

        <?php if (isset($ddw_texts['textrequirederror'])) : ?>
            <?php foreach($ddw_texts['textrequirederror'] as $key=>$text) : ?>
                <?php if ($text != '') : ?>
                    required_error = '<?php echo $text; ?>';
                <? endif; ?>
            <?php endforeach; ?>
        <? endif; ?>

		var ddw = new DDWFrontEnd();
	});
</script>