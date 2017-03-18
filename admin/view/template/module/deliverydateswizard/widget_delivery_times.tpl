<div id="widget-delivery-times">
	<div id="widget-deliverytimes-languages" class="htabs">
		<?php foreach ($languages as $language) { ?>
			<a href="#widget-deliverytimes-language<?php echo $language['language_id']; ?>"><img src="view/image/flags/<?php echo $language['image']; ?>" title="<?php echo $language['name']; ?>" /> <?php echo $language['name']; ?></a>
		<?php } ?>
	</div>
	
	<?php foreach ($languages as $language) : ?>	
		<div id="widget-deliverytimes-language<?php echo $language['language_id']; ?>">
			Add delivery time slot: <input name="delivery-time-slot-<?php echo $language['language_id']; ?>" class="delivery-time-slot-text" type="text"> <input type="button" value="add" class="btn-delivery-time-slot-add" data-language-id="<?php echo $language['language_id']; ?>">
			<ul id="delivery-times-<?php echo $language['language_id']; ?>" class="sortable deliverytimes-widget" data-language-id="<?php echo $language['language_id']; ?>">
				<?php if (isset($delivery_times[$language{'language_id'}])) : ?>
					<?php foreach($delivery_times[$language{'language_id'}] as $key=>$item) : ?>
						<li class="ui-state-default">
							<span class="ui-icon ui-icon-clock"></span><span class="text"><?php $item->text;?></span><a href="#" class="ui-icon ui-icon-closethick remove"></a>
						</li>
					<?php endforeach; ?>
				<?php endif; ?>
			</ul>
		</div>
	<?php endforeach; ?>
	
	<!-- Used for JS cloning only -->
	<ul id="deliverytimes-sortlist-template" style="display:none;">
		<li class="ui-state-default"><span class="ui-icon ui-icon-clock"></span><span class="text"></span><a href="#" class="ui-icon ui-icon-closethick remove"></a></li>
	</ul>
	<!-- / Used for JS cloning only -->
</div>	
<style>
	.deliverytimes-widget { list-style-type:none; margin:0px; padding:4px; width:40%; background-color:#f1f1f1; }
	.deliverytimes-widget li { position:relative; padding:10px; margin-bottom:4px;}
	.deliverytimes-widget .ui-state-default .ui-icon { background-image: url("<?php echo $admin_url;?>view/javascript/jquery/ui/themes/ui-lightness/images/ui-icons_228ef1_256x240.png") !important; display:inline-block; position:absolute; top:2px; }	
	.deliverytimes-widget .ui-state-default .ui-icon-closethick { right:10px; top:12px;}
	.deliverytimes-widget .ui-state-default .ui-icon-clock { position:relative; margin-right:5px;}
		
</style>
