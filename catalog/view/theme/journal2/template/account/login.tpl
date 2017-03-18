<?php echo $header; ?>
<div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
</div>
<?php if ($success) { ?>
<div class="success"><?php echo $success; ?></div>
<?php } ?>
<?php if ($error_warning) { ?>
<div class="warning"><?php echo $error_warning; ?></div>
<?php } ?>
<?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content"><h1 class="heading-title"><?php echo $heading_title; ?></h1><?php echo $content_top; ?>
  <div class="login-content">
    <div class="left">
      <h2 class="secondary-title"><?php echo $text_new_customer; ?></h2>
      <div class="content">
        <div class="login-wrap">
            <p><b><?php echo $text_register; ?></b></p>
            <p><?php echo $text_register_account; ?></p>
        </div>
        <hr>
        <a href="<?php echo $register; ?>" class="button"><?php echo $button_continue; ?></a></div>
    </div>
    <div class="right">
      <h2 class="secondary-title"><?php echo $text_returning_customer; ?></h2>
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data">
        <div class="content">
          <div class="login-wrap">
              <p><?php echo $text_i_am_returning_customer; ?></p>
              <b><?php echo $entry_email; ?></b>
              <input type="text" name="email" value="<?php echo $email; ?>" />
              <b><?php echo $entry_password; ?></b>
              <input type="password" name="password" value="<?php echo $password; ?>" />
              <a href="<?php echo $forgotten; ?>"><?php echo $text_forgotten; ?></a><br />
          </div>
          <hr>
            <button type="submit" class="button"><?php echo $button_login; ?></button>
          <?php if ($redirect) { ?>
          <input type="hidden" name="redirect" value="<?php echo $redirect; ?>" />
          <?php } ?>
        </div>
      </form>
    </div>
  </div>
  <?php echo $content_bottom; ?></div>
<script type="text/javascript"><!--
$('#login input').keydown(function(e) {
	if (e.keyCode == 13) {
		$('#login').submit();
	}
});
//--></script> 
<?php echo $footer; ?>