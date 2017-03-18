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
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/total.png" alt="" /> <?php echo $heading_title; ?></h1>
      <div class="buttons"><a onclick="$('#form').submit();" class="button"><?php echo $button_save; ?></a><a href="<?php echo $cancel; ?>" class="button"><?php echo $button_cancel; ?></a></div>
    </div>
    <div class="content">
      <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
        <table class="form">
          <tr>
            <td><?php echo $entry_status; ?></td>
            <td><select name="reward_status">
                <?php if ($reward_status) { ?>
                <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                <option value="0"><?php echo $text_disabled; ?></option>
                <?php } else { ?>
                <option value="1"><?php echo $text_enabled; ?></option>
                <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                <?php } ?>
              </select></td>
          </tr>
          <tr>
            <td><?php echo $entry_sort_order; ?></td>
            <td><input type="text" name="reward_sort_order" value="<?php echo $reward_sort_order; ?>" size="1" /></td>
          </tr>
        </table>
		
		
		
		
		<div id="tab-intervalo">
		  <table class="list">
                <thead>
                    <tr>
                        <td class="left">Valor Inicial</td>
                        <td class="left">Valor Final	</td>
                        <td class="left">Pontos</td>
                        <td class="left"></td>
                    </tr>
                </thead>
                <tbody>
                    <?php $intervaloCount = 0; ?>
                    <?php foreach ($intervalos as $intervalo){ ?>
                        <?php $intervaloCount++ ?>
                    
                        <tr id="intervalo-row<?php echo $intervaloCount ?>">
                            <td class="left">
                                
								<input type="text" name="intervalo_lot['<?php echo $intervaloCount;?>'][inicial]" value="<?php echo $intervalo['valor_inicial'];?>">
                            </td>
                            <td class="left">
                               <input type="text" name="intervalo_lot['<?php echo $intervaloCount;?>'][final]" value="<?php echo $intervalo['valor_final'];?>">
                            </td>
							<td class="left">
                                <input type="text" name="intervalo_lot['<?php echo $intervaloCount;?>'][pontos]" value="<?php echo $intervalo['pontos'];?>">
                            </td>
                            <td class="left">
                                <a class="button" onclick="$('#intervalo-row<?php echo $intervaloCount ?>').remove()">Remover</a>
                            </td>
                        </tr>
                    
                    <?php }; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="3"></td>
                        <td class="left"><a onclick="addintervalo()" class="button">Adicionar Intervalo</a></td>
                    </tr>
                </tfoot>
            </table>
		
		</div>
		
		
		
		
		
      </form>
    </div>
  </div>
</div>
<?php echo $footer; ?>



<script type="text/javascript"><!--
var intervaloCount = <?php echo $intervaloCount ?>;

function addintervalo() {
    intervaloCount++;
    
    var html = '';
	
    html += '<tr id="intervalo-row' + intervaloCount + '" style="background-color: #ffd766;">';
    html += '  <td class="left"  style="background-color: #ffd766;">';
    html += '    <input type="text" name="intervalo_lot[' + intervaloCount + '][inicial]" value=""  />';
    html += '  </td>';
    html += '  <td class="left"  style="background-color: #ffd766;">';
    html += '    <input type="text" name="intervalo_lot[' + intervaloCount + '][final]" value=""  />';

    html += '  </td>';    
	html += '  <td class="left"  style="background-color: #ffd766;">';
    html += '    <input type="text" name="intervalo_lot[' + intervaloCount + '][pontos]" value="" />';

    html += '  </td>';
    html += '  <td class="left"  style="background-color: #ffd766;">';
    html += '    <a class="button" onclick="$(\'#intervalo-row' + intervaloCount + '\').remove()">Remover</a>';
    html += '  </td>';
    html += '</tr>';
    
    $('#tab-intervalo table tbody').append(html);
	
	
}

</script>