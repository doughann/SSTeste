<?php if ($modules) { ?>
<div id="column-left" class="side-column <?php echo $this->journal2->settings->get('flyout_column_left_active') ? 'flyout flyout-left' : ''; ?>">
  <?php foreach ($modules as $module) { ?>
  <?php echo $module; ?>
  <?php } ?>
</div>
<?php } ?> 
