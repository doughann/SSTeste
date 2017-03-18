<?php if ($options) { ?>
      <div class="options">
        <h2><?php echo $productType . ' ' . $lang->get('text_options'); ?></h2>
        <br />
        <?php foreach ($options as $option) {
        	$thisValue = isset($defaults[$option['product_option_id']])?$defaults[$option['product_option_id']]:''; 
        ?>
        <?php if ($option['type'] == 'select') { ?>
        <div class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <select name="<?php echo $discountType?>[<?php echo $productRow?>][<?php echo $productType?>option][<?php echo $option['product_option_id']; ?>]">
            <option value=""><?php echo $lang->get('text_select'); ?></option>
            <?php foreach ($option['option_value'] as $option_value) { ?>
            <option value="<?php echo $option_value['product_option_value_id']; ?>" <?php if($option_value['product_option_value_id']==$thisValue)echo 'selected'?>><?php echo $option_value['name']; ?>
            </option>
            <?php } ?>
          </select>
        </div>
        <br />
        <?php } ?>
        
        <?php if ($option['type'] == 'radio') { ?>
        <div class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <?php foreach ($option['option_value'] as $option_value) { ?>
          <label>
          <input type="radio" name="<?php echo $discountType?>[<?php echo $productRow?>][<?php echo $productType?>option][<?php echo $option['product_option_id']; ?>]" value="<?php echo $option_value['product_option_value_id']; ?>" <?php if($option_value['product_option_value_id']==$thisValue)echo 'checked="checked"'?> />
          <?php echo $option_value['name']; ?>
          </label>
          <br />
          <?php } ?>
        </div>
        <br />
        <?php } ?>
        
        <?php if ($option['type'] == 'checkbox') {
        	if(!is_array($thisValue)){
        		$thisValue = (array)$thisValue;
        	}
        ?>
        <div class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <?php foreach ($option['option_value'] as $option_value) { ?>
          <label><input type="checkbox" name="<?php echo $discountType?>[<?php echo $productRow?>][<?php echo $productType?>option][<?php echo $option['product_option_id']; ?>][]" value="<?php echo $option_value['product_option_value_id']; ?>" <?php if(in_array($option_value['product_option_value_id'], $thisValue))echo 'checked="checked"'?> />
          <?php echo $option_value['name']; ?>
          </label>
          <br />
          <?php } ?>
        </div>
        <br />
        <?php } ?>
        
        
        <?php if ($option['type'] == 'text') { ?>
        <div class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <input type="text" name="<?php echo $discountType?>[<?php echo $productRow?>][<?php echo $productType?>option][<?php echo $option['product_option_id']; ?>]" value="<?php echo ($thisValue!=''?$thisValue:$option['option_value']); ?>" />
        </div>
        <br />
        <?php } ?>
        
        <?php if ($option['type'] == 'textarea') { ?>
        <div class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <textarea name="<?php echo $discountType?>[<?php echo $productRow?>][<?php echo $productType?>option][<?php echo $option['product_option_id']; ?>]" cols="40" rows="5"><?php echo ($thisValue!=''?$thisValue:$option['option_value']); ?></textarea>
        </div>
        <br />
        <?php } ?>
        
        <?php if ($option['type'] == 'date') { ?>
        <div class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <input type="text" name="<?php echo $discountType?>[<?php echo $productRow?>][<?php echo $productType?>option][<?php echo $option['product_option_id']; ?>]" value="<?php echo ($thisValue!=''?$thisValue:$option['option_value']); ?>" class="date" />
        </div>
        <br />
        <?php } ?>
        
        <?php if ($option['type'] == 'datetime') { ?>
        <div class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <input type="text" name="<?php echo $discountType?>[<?php echo $productRow?>][<?php echo $productType?>option][<?php echo $option['product_option_id']; ?>]" value="<?php echo ($thisValue!=''?$thisValue:$option['option_value']); ?>" class="datetime" />
        </div>
        <br />
        <?php } ?>
        
        <?php if ($option['type'] == 'time') { ?>
        <div class="option">
          <?php if ($option['required']) { ?>
          <span class="required">*</span>
          <?php } ?>
          <b><?php echo $option['name']; ?>:</b><br />
          <input type="text" name="<?php echo $discountType?>[<?php echo $productRow?>][<?php echo $productType?>option][<?php echo $option['product_option_id']; ?>]" value="<?php echo ($thisValue!=''?$thisValue:$option['option_value']); ?>" class="time" />
        </div>
        <br />
        <?php } ?>
        
        
        
        
        <?php } ?>
      </div>
      <?php } ?>