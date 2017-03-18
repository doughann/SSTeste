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
	<?php if ($success) { ?>
		<div class="success"><?php echo $success; ?></div>
	<?php } ?>
	<div id="tabs" class="htabs">
		<a href="#tab_general"><?php echo $tab_general; ?></a>
		<a href="#tab_sync"><?php echo $tab_sync; ?></a>
		<a id="help_tab" href="#tab_help"><?php echo $tab_help; ?></a>
		<a id="news_and_updates" href="#tab_news_and_updates"><?php echo $tab_news_and_updates; ?></a>
	</div>

	<div id="tab_general">
		<div class="box">
			<div class="heading">
				<h1><img src="view/image/setting.png" alt="" /> <?php echo $heading_title; ?></h1>
				<div class="buttons">
					<a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a>
					<a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a>
				</div>
			</div>
			<div class="content">
				<form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form" name="cmconfig_fname" >
					<table class="form">
						<tr>
							<td valign="top">
								<span class="required">*</span> <?php echo $entry_api_key; ?><br /><span class="help"><?=$text_apikey_help?></span>
							</td>
							<td>
								<input placeholder="<?=$text_apikey_placeholder?>" type="text" name="mailchimp_api" value="<?php echo $mailchimp_api; ?>" size="80" id="mc_api" />
								<a id="list-load-btn" href="#" class="button" onclick="loadLists();return false;"><?=$text_btn_load_list?></a>
								<?php if ($error_api) { ?>
									<span class="error"><?php echo $error_api; ?></span>
								<?php } ?>
							</td>
						</tr>
					</table>
					<table class="list">
						<thead>
							<tr>
								<td class="right"><?php echo $entry_customer_group; ?></td>
								<td class="left"><?php echo $entry_list_id; ?></td>
							</tr>
						</thead>
						<tbody>
						<?php
						foreach($customer_groups as $customer_group){
							$error_list_id   = 'error_mc_list_'.$customer_group['customer_group_id'];
						?>
							<tr>
								<td class="right" width="150px" style="padding: 5px">
									<label><?=$customer_group['name'];?></label>
								</td>
								<td>
									<select multiple="multiple" class="mc_lists form-control" name="mailchimp_list_<?=$customer_group['customer_group_id']?>[]" id="mc_list__<?=$customer_group['customer_group_id']?>"></select>
									<?php if (isset($$error_list_id)) { ?>
									<div class="text-danger"><?php echo $$error_list_id; ?></div>
									<?php } ?>
								</td>
							</tr>
						<?php } ?>
						</tbody>
					</table>
				</form>
			</div>
		</div>
	</div>
	<div id="tab_sync">
		<a class="btn btn-primary" href="<?=$auto_sync_link?>" onclick="return check2proceed();"><?=$click2sync?></a>
		<h3 class="alert alert-info"><?=$sync_note?></h3>
	</div>
	<div id="tab_help">&nbsp;</div>
	<div id="tab_news_and_updates">&nbsp;</div>
</div>
<style type="text/css">
.alert-info {
    background-color: #d9eef9;
    border-color: #baeaf4;
    color: #115376;
}
.alert {
    border: 1px solid transparent;
    border-radius: 3px;
    margin-bottom: 17px;
    padding: 10px;
}
.btn {
  -moz-user-select: none;
  background-image: none;
  border: 1px solid transparent;
  border-radius: 3px;
  cursor: pointer;
  display: inline-block;
  font-size: 12px;
  font-weight: normal;
  line-height: 1.42857;
  margin-bottom: 0;
  padding: 8px 13px;
  text-align: center;
  vertical-align: middle;
  white-space: nowrap;
    text-decoration: none !important;
}
.btn-primary {
    background-color: #1e91cf;
    border-color: #1978ab;
    color: #ffffff !important;
}
.btn-primary:hover, .btn-primary:focus, .btn-primary.focus, .btn-primary:active, .btn-primary.active{
    background-color: #1872a2;
    border-color: #115376;
    color: #ffffff !important;
    text-decoration: none !important;
}
.btn:hover, .btn:focus, .btn.focus {
    color: #555555;
    text-decoration: none !important;
}
</style>
<script type="text/javascript">
/* News and Updates */
$(function(){
	$('a#news_and_updates').bind('click', function(){
		$('#tab_news_and_updates').html('<iframe style="border: 0 none;height: 800px;width: 100%;" src="http://webby-blog.com/extension-news-and-update.html?'+(new Date().getTime())+'"></iframe>');
	});
        
    $('a#help_tab').bind('click', function(){
		$('#tab_help').html('<iframe style="border: 0 none;height: 800px;width: 100%;" src="http://webby-blog.com/mailchimp-update.html?'+(new Date().getTime())+'"></iframe>');
	});
});
/* News and Updates Ends */
function check2proceed(){
	if(confirm('<?=$sync_warning?>'))	{
		return true;
	} 
	return false
}
function loadLists(){

	if(can_start){
		can_start = false;
		if($.trim($('#mc_api').val()).length>0){
			$.ajax({
				async: true,
				url: 'index.php?route=mail_chimp/setting/getlist&token=<?php echo $token; ?>&api_key=' + $('#mc_api').val(),
				dataType: 'json',
				beforeSend: function() {
					$('.mc_lists').after('<span class="wait_list">&nbsp;<?=$text_loading?></span>').fadeOut(0);
				},		
				complete: function() {
					$('.wait_list').remove();
					$('.mc_lists').fadeIn('slow');
				},			
				success: function(json) {	
					can_start = true;
					var html = '<option value=""><?php echo $text_select; ?></option>';

					if(json.total == 0){
						alert('Sorry no list found, Please create list in MailChimp then proceed.');
						html += '<option value="0" selected="selected"><?php echo $text_none; ?></option>';
					} else {
						$.each(json.data, function(mck, mcv){
							html += '<option value="' + mcv.id + '">' + mcv.name + '</option>';
						});
					}

					$('.mc_lists').each(function(){
						$(this).html(html);
					});

					<?php 
					foreach($customer_groups as $customer_group){ 
						$mc_list_custgrp = 'mailchimp_list_'.$customer_group['customer_group_id'];
						$current_val = '["'.implode('","',($$mc_list_custgrp)).'"]';
						if(strlen($current_val) > 0){
					?>
						$('#mc_list__<?=$customer_group['customer_group_id']?>').val(<?=$current_val?>);
					<?php
						}
					} 
					?>
				},
				error: function(xhr, ajaxOptions, thrownError) {
					can_start = true;
					loadLists();
				}
			});
		}		
	}


}
$(function(){
	can_start= true;

	$('#mc_api').bind('blur', function() {
		
		if($.trim($( "#mc_api" ).val()).length>0){
			$('#list-load-btn').removeClass('disabled');	
		}

		loadLists();
	});	
	$( "#mc_api" ).keyup(function() {
		if($.trim($( "#mc_api" ).val()).length>0){
			$('#list-load-btn').removeClass('disabled');	
		} else {
			$('#list-load-btn').removeClass('disabled').addClass('disabled');	
		}
	});
	if($.trim($('#mc_api').val()).length>0){
		loadLists();
	}
});
</script>
<script type="text/javascript"><!--
$('#tabs a').tabs(); 
//--></script> 
<?php echo $footer; ?>