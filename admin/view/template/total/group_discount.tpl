<?php echo $header;?>
<style>
 select.discount_row{
     width: 250px;
 }
 .list tbody td{
 	vertical-align: top;
}
</style>
<script type="text/javascript" src="/catalog/view/javascript/jquery/ui/jquery-ui-timepicker-addon.js"></script>
<script>
$(function(){
	initProducts();
	initOptions();
})

function initProducts(){
	$('.discount-product').on('change', function(){
		var obj = $(this)
		$.post('index.php?route=total/group_discount/get_options&token=<?php echo $token; ?>&filter_name=',{
				productId: $(this).val(),
				productType: $(this).attr('name').match(/product./)[0],
				productRow: $(this).attr('name').match(/\d+/)[0]
			},function(data){
			obj.next().html(data);
			initOptions();
		})
	})
}

function initCategories(){
	$('.discount-category').on('change', function(){
		var obj = $(this)
		$.post('index.php?route=total/group_discount/get_options_category&token=<?php echo $token; ?>&filter_name=',{
				productId: $(this).val(),
				productType: $(this).attr('name').match(/product./)[0],
				productRow: $(this).attr('name').match(/\d+/)[0]
			},function(data){
			obj.next().html(data);
			initOptions();
		})
	})
}

function initOptions(){
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});
	$('.datetime').datetimepicker({
		dateFormat: 'yy-mm-dd',
		timeFormat: 'h:m'
	});
	$('.time').timepicker({timeFormat: 'h:m'});
}
</script>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
  <?php if ($error_warning) { ?>
  <div class="warning"><?php echo $error_warning; ?></div>
  <?php } ?>
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/total.png" alt="" /> <?php echo $lang->get('page_title'); ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $lang->get('button_save'); ?></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><?php echo $lang->get('button_cancel'); ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><?php echo $lang->get('entry_status'); ?>&nbsp;&nbsp;&nbsp;<select name="group_discount_status">
                <?php if ($group_discount_status) { ?>
                <option value="1" selected="selected"><?php echo $lang->get('text_enabled'); ?></option>
                <option value="0"><?php echo $lang->get('text_disabled'); ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $lang->get('text_enabled'); ?></option>
                <option value="0" selected="selected"><?php echo $lang->get('text_disabled'); ?></option>
                <?php } ?>
              </select></td>
            <td><?php echo $lang->get('entry_sort_order'); ?>&nbsp;&nbsp;&nbsp;<input type="text" name="group_discount_sort_order" value="<?php echo $group_discount_sort_order; ?>" size="1" /></td>
          </tr>
		  </table>
          <h2><?php echo $lang->get('product_discount_label')?></h2>
          <table id="module" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $lang->get('entry_column_productA'); ?></td>
              <td class="left"><?php echo $lang->get('entry_column_productB'); ?></td>
              <td class="left"><?php echo $lang->get('entry_column_productC'); ?></td>
              <td class="left"><?php echo $lang->get('entry_column_productD'); ?></td>
              <td class="right"><?php echo $lang->get('entry_column_discountType'); ?></td>
              <td class="right"><?php echo $lang->get('entry_column_discount'); ?></td>
              <td></td>
            </tr>
          </thead>
          <? $module_row = 0;
          foreach($discounts as $discount):?>
          <tbody id="module-row<?=$module_row?>">
            <tr>
                <td class="left">
                	<label><input type="hidden" name="group_discount[<?=$module_row?>][productAhide]" value="0">
                	<input type="checkbox" name="group_discount[<?=$module_row?>][productAhide]" value="1" <?php 
                	if(isset($discount['productAhide'])&&$discount['productAhide']==1)echo 'checked="checked"'?>>Hide discount</label></br>
                	<select name="group_discount[<?=$module_row?>][productA]" class="discount_row discount-product">
                    <?foreach($products as $product):?>
                        <option value="<?=$product['product_id']?>" <?=($discount['productA']==$product['product_id'])?'selected="selected"':''?>><?=$product['name']?></option>  
                    <?endforeach?>
                    </select><div><?php echo $this->renderProductOptions($lang, $discount['productA'], $module_row, 'productA', isset($discount['productAoption'])?$discount['productAoption']:array());?></div></td>
                <td class="left">
                	<label><input type="hidden" name="group_discount[<?=$module_row?>][productBhide]" value="0">
                	<input type="checkbox" name="group_discount[<?=$module_row?>][productBhide]" value="1" <?php 
                	if(isset($discount['productBhide'])&&$discount['productBhide']==1)echo 'checked="checked"'?>>Hide discount</label></br>
                	<select name="group_discount[<?=$module_row?>][productB]" class="discount_row discount-product">
                    <option></option>
                    <?foreach($products as $product):?>
                        <option value="<?=$product['product_id']?>" <?=($discount['productB']==$product['product_id'])?'selected="selected"':''?>><?=$product['name']?></option>  
                    <?endforeach?>
                    </select><div><?php echo $this->renderProductOptions($lang, $discount['productB'], $module_row, 'productB', isset($discount['productBoption'])?$discount['productBoption']:array());?></div></td>
                <td class="left">
                	<label><input type="hidden" name="group_discount[<?=$module_row?>][productChide]" value="0">
                	<input type="checkbox" name="group_discount[<?=$module_row?>][productChide]" value="1" <?php 
                	if(isset($discount['productChide'])&&$discount['productChide']==1)echo 'checked="checked"'?>>Hide discount</label></br>
                	<select name="group_discount[<?=$module_row?>][productC]" class="discount_row discount-product">
                    <option></option>
                    <?foreach($products as $product):?>
                        <option value="<?=$product['product_id']?>" <?=($discount['productC']==$product['product_id'])?'selected="selected"':''?>><?=$product['name']?></option>  
                    <?endforeach?>
                    </select><div><?php echo $this->renderProductOptions($lang, $discount['productC'], $module_row, 'productC', isset($discount['productCoption'])?$discount['productCoption']:array());?></div></td>
                <td class="left">
                	<label><input type="hidden" name="group_discount[<?=$module_row?>][productDhide]" value="0">
                	<input type="checkbox" name="group_discount[<?=$module_row?>][productDhide]" value="1" <?php 
                	if(isset($discount['productDhide'])&&$discount['productDhide']==1)echo 'checked="checked"'?>>Hide discount</label></br>
                	<select name="group_discount[<?=$module_row?>][productD]" class="discount_row discount-product">
                    <option></option>
                    <?foreach($products as $product):?>
                        <option value="<?=$product['product_id']?>" <?=($discount['productD']==$product['product_id'])?'selected="selected"':''?>><?=$product['name']?></option>  
                    <?endforeach?>
                    </select><div><?php echo $this->renderProductOptions($lang, $discount['productD'], $module_row, 'productD', isset($discount['productDoption'])?$discount['productDoption']:array());?></div></td>
                <td class="left"></br><select name="group_discount[<?=$module_row?>][discountType]">
                        <option value="percent" <?=$discount['discountType']=='percent'?'selected="selected"':''?>><?=$lang->get('entry_discount_type_percent')?></option>
                        <option value="fixed" <?=$discount['discountType']=='fixed'?'selected="selected"':''?>><?=$lang->get('entry_discount_type_fixed')?></option>
                        </select></td>
                <td class="left"></br><input type="text" name="group_discount[<?=$module_row?>][discount]" value="<?=$discount['discount']?>"></td>
                <td class="left"></br><a onclick="$('#module-row<?=$module_row?>').remove();" class="button"><?php echo $lang->get('button_remove'); ?></a></td>
            </tr>
          </tbody>
          
          <?$module_row++?>
          <?endforeach?>
          
          <tfoot>
            <tr>
              <td colspan="5"></td>
              <td class="left"><a onclick="addModule();" class="button"><?php echo $lang->get('button_add_discount'); ?></a></td>
            </tr>
          </tfoot>
        </table>
        
        <h2><?php echo $lang->get('category_discount_label')?></h2>
        <table id="category" class="list">
          <thead>
            <tr>
              <td class="left"><?php echo $lang->get('entry_column_category'); ?></td>
              <td class="left"><?php echo $lang->get('entry_column_productB'); ?></td>
              <td class="left"><?php echo $lang->get('entry_column_productC'); ?></td>
              <td class="left"><?php echo $lang->get('entry_column_productD'); ?></td>
              <td class="right"><?php echo $lang->get('entry_column_discountType'); ?></td>
              <td class="right"><?php echo $lang->get('entry_column_discount'); ?></td>
              <td></td>
            </tr>
          </thead>
          <?$category_row = 0;?>
          
          <?foreach($category_discounts as $discount):?>
          <tbody id="category-row<?=$category_row?>">
            <tr>
                <td class="left"><select name="category_discount[<?=$category_row?>][categoryId]" class="discount_row">
                    <?foreach($categories as $category):?>
                        <option value="<?=$category['category_id']?>" <?=($discount['categoryId']==$category['category_id'])?'selected="selected"':''?>><?=$category['name']?></option>  
                    <?endforeach?>
                    </select></td>
                <td class="left"><select name="category_discount[<?=$category_row?>][productB]" class="discount_row">
                	<option></option>
                    <?foreach($products as $product):?>
                        <option value="<?=$product['product_id']?>" <?=($discount['productB']==$product['product_id'])?'selected="selected"':''?>><?=$product['name']?></option>  
                    <?endforeach?>
                    </select><div><?php echo $this->renderProductOptions($lang, $discount['productB'], $category_row, 'productB', isset($discount['productBoption'])?$discount['productBoption']:array(), 'category_discount');?></div></td>
                <td class="left"><select name="category_discount[<?=$category_row?>][productC]" class="discount_row">
                    <option></option>
                    <?foreach($products as $product):?>
                        <option value="<?=$product['product_id']?>" <?=($discount['productC']==$product['product_id'])?'selected="selected"':''?>><?=$product['name']?></option>  
                    <?endforeach?>
                    </select><div><?php echo $this->renderProductOptions($lang, $discount['productC'], $category_row, 'productC', isset($discount['productCoption'])?$discount['productCoption']:array(), 'category_discount');?></div></td>
                <td class="left"><select name="category_discount[<?=$category_row?>][productD]" class="discount_row">
                    <option></option>
                    <?foreach($products as $product):?>
                        <option value="<?=$product['product_id']?>" <?=($discount['productD']==$product['product_id'])?'selected="selected"':''?>><?=$product['name']?></option>  
                    <?endforeach?>
                    </select><div><?php echo $this->renderProductOptions($lang, $discount['productD'], $category_row, 'productD', isset($discount['productDoption'])?$discount['productDoption']:array(), 'category_discount');?></div></td>
                <td class="left"><select name="category_discount[<?=$category_row?>][discountType]">
                        <option value="percent" <?=$discount['discountType']=='percent'?'selected="selected"':''?>><?=$lang->get('entry_discount_type_percent')?></option>
                        <option value="fixed" <?=$discount['discountType']=='fixed'?'selected="selected"':''?>><?=$lang->get('entry_discount_type_fixed')?></option>
                        </select></td>
                <td class="left"><input type="text" name="category_discount[<?=$category_row?>][discount]" value="<?=$discount['discount']?>"></td>
                <td class="left"><a onclick="$('#category-row<?=$category_row?>').remove();" class="button"><?php echo $lang->get('button_remove'); ?></a></td>
            </tr>
          </tbody>
          
          <?$category_row++?>
          <?endforeach?>
          
          <tfoot>
            <tr>
              <td colspan="5"></td>
              <td class="left"><a onclick="addCategoryDiscount();" class="button"><?php echo $lang->get('button_add_discount'); ?></a></td>
            </tr>
          </tfoot>
        </table>
      </form>
    </div>
  </div>
</div>

<script type="text/javascript"><!--
var module_row = <?php echo $module_row; ?>;
var category_row = <?php echo $category_row; ?>;

function addModule() {
    
    htmlOptions = '<option></option>';
    <?foreach($products as $product):?>
    htmlOptions += '<option value="<?=$product['product_id']?>"><?=addslashes($product['name'])?></option>'
    <?endforeach?>
    
    html  = '<tbody id="module-row' + module_row + '">';
    html += '  <tr>';
    
    html += '    <td class="left">'
    html += '<label><input type="hidden" name="group_discount['+module_row+'][productAhide]" value="0">'
    html += '<input type="checkbox" name="group_discount['+module_row+'][productAhide]" value="1">Hide discount</label></br>'
    html +='<select name="group_discount['+module_row+'][productA]" class="discount_row discount-product">'
    html += htmlOptions
    html += '</select><div></div></td>';
    
    html += '    <td class="left">'
   	html += '<label><input type="hidden" name="group_discount['+module_row+'][productBhide]" value="0">'
    html += '<input type="checkbox" name="group_discount['+module_row+'][productBhide]" value="1">Hide discount</label></br>'
    html += '<select name="group_discount['+module_row+'][productB]" class="discount_row discount-product">'
    html += htmlOptions
    html += '</select><div></div></td>';
    
    html += '    <td class="left">'
    html += '<label><input type="hidden" name="group_discount['+module_row+'][productChide]" value="0">'
    html += '<input type="checkbox" name="group_discount['+module_row+'][productChide]" value="1">Hide discount</label></br>'
    html += '<select name="group_discount['+module_row+'][productC]" class="discount_row discount-product">'
    html += htmlOptions
    html += '</select><div></div></td>';
    
    html += '    <td class="left">'
    html += '<label><input type="hidden" name="group_discount['+module_row+'][productDhide]" value="0">'
    html += '<input type="checkbox" name="group_discount['+module_row+'][productDhide]" value="1">Hide discount</label></br>'
    html += '<select name="group_discount['+module_row+'][productD]" class="discount_row discount-product">'
    html += htmlOptions
    html += '</select><div></div></td>';
    
    html += '    <td class="left"></br><select name="group_discount['+module_row+'][discountType]">'
    html += '<option value="percent"><?=$lang->get('entry_discount_type_percent')?></option>'
    html += '<option value="fixed"><?=$lang->get('entry_discount_type_fixed')?></option>'
    html += '</select></td>';
    
    html += '    <td class="left"></br><input type="text" name="group_discount['+module_row+'][discount]"></td>'
    
    html += '    <td class="left"></br><a onclick="$(\'#module-row' + module_row + '\').remove();" class="button"><?php echo $lang->get('button_remove'); ?></a></td>';
    html += '  </tr>';
    html += '</tbody>';
    
    $('#module tfoot').before(html);
    initProducts();
    module_row++;
}

function addCategoryDiscount() {
    
    htmlOptions = '<option></option>';
    <?foreach($products as $product):?>
    htmlOptions += '<option value="<?=$product['product_id']?>"><?=addslashes($product['name'])?></option>'
    <?endforeach?>
    
    html  = '<tbody id="category-row' + category_row + '">';
    html += '  <tr>';
    
    html += '    <td class="left"><select name="category_discount['+category_row+'][categoryId]" class="discount_row">'
    <?foreach($categories as $category):?>
    html += '<option value="<?=$category['category_id']?>"><?=addslashes($category['name'])?></option>'
    <?endforeach?>
    html += '</select></td>';
    
    html += '    <td class="left"><select name="category_discount['+category_row+'][productB]" class="discount_row discount-category">'
    html += htmlOptions
    html += '</select><div></div></td>';
    
    html += '    <td class="left"><select name="category_discount['+category_row+'][productC]" class="discount_row discount-category">'
    html += htmlOptions
    html += '</select><div></div></td>';
    
    html += '    <td class="left"><select name="category_discount['+category_row+'][productD]" class="discount_row discount-category">'
    html += htmlOptions
    html += '</select><div></div></td>';
    
    html += '    <td class="left"><select name="category_discount['+category_row+'][discountType]">'
    html += '<option value="percent"><?=$lang->get('entry_discount_type_percent')?></option>'
    html += '<option value="fixed"><?=$lang->get('entry_discount_type_fixed')?></option>'
    html += '</select></td>';
    
    html += '    <td class="left"><input type="text" name="category_discount['+category_row+'][discount]"></td>'
    
    html += '    <td class="left"><a onclick="$(\'#category-row' + category_row + '\').remove();" class="button"><?php echo $lang->get('button_remove'); ?></a></td>';
    html += '  </tr>';
    html += '</tbody>';
    
    $('#category tfoot').before(html);
    initCategories();
    category_row++;
}
//--></script>

<?php echo $footer; ?>