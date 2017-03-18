<?php echo $header; ?>
<div id="content">
  <div class="breadcrumb">
    <?php foreach ($breadcrumbs as $breadcrumb) { ?>
    <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
    <?php } ?>
  </div>
 <form action="" method="post" enctype="multipart/form-data" id="form">
  <div class="box">
    <div class="heading">
      <h1><img src="view/image/order.png" alt="" /> Relatório</h1>
      <div class="buttons">
				<select name="filter_order_status_id">
                  <option value="">*todos*</option>
                  <?php $filtrostatus="Todos";?>
                  <?php foreach ($order_statuses as $order_status) { ?>
                  <?php if ($order_status['order_status_id'] == $filter_order_status_id) { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; $filtrostatus=$order_status['name'];?></option>
                  <?php } else { ?>
                  <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                  <?php } ?>
                  <?php } ?>
                </select>

	  <select name="filter_tipo">
                  <option value="">todos</option>
				  <option value="sg" <?php if($filter_tipo=="sg"){echo "selected";}?>>Sagawa</option>
                  <option value="lm" <?php if($filter_tipo=="lm"){echo "selected";}?>>Local Manhã</option>
                  <option value="ln" <?php if($filter_tipo=="ln"){echo "selected";}?>>Local Noite</option>
                  <option value="bl" <?php if($filter_tipo=="bl"){echo "selected";}?>>Buscar na Loja</option>
		</select>

				  De <input type="text" name="filter_delivery_date" value="<?php echo $filter_delivery_date; ?>" size="12" class="date" /> a
          <input type="text" name="filter_delivery_date2" value="<?php echo $filter_delivery_date2; ?>" size="12" class="date" />
          <a onclick="filter('');" class="button">Filtrar</a> <a onclick="imprimir_tudo();" class="button">Imprimir</a>
          <!-- <a onclick="exportar();" class="button">Exportar</a> -->
          <a onclick="filter('exportar');" class="button">Exportar</a>


	</div>
    </div>
    <div class="content" id="imprimivel">
      <?php //echo "<pre>";print_r($dsagawa);echo "</pre>";	?>
      <?php //print_r($results);?>
      <?php $numcols=3;?>
	  <?php if($filter_tipo=="sg" or $filter_tipo==""){?>
        <table class="list">
          <thead>
		  <tr >
			<td class="left" colspan="<?php echo ($numcols+1);?>"><strong>Sagawa</strong> - <?php echo "de ".$filter_delivery_date." a ".$filter_delivery_date2; ?> - <?php echo $filtrostatus; ?> </td>
		  </tr>
            <tr>
             <td class="center" style="width:20%;" >Pedido n°</td>

              <td class="left" style="width:20%;" >Hora</td>

              <td class="left" style="width:60%;" colspan="2">Produtos</td>


            </tr>
          </thead>
          <tbody>

			<?php if(isset($dsagawa)){foreach ($dsagawa as $sagawa){?>
            <tr>

              <td class="right" ><a href="index.php?route=sale/order/info&order_id=<?php echo $sagawa['order_id'];?>&token=<?php echo $_SESSION['token'];?>"><?php echo $sagawa['order_id'];?></a></td>
              <td class="left" ><?php echo $sagawa['ddw_time_slot'];?></td>
              <td class="left" colspan="2">
				<?php


         foreach ($sagawa['products'] as $product) { ?>
				<?php echo "-".$product['quantity']." - ".$product['name'];
				//print_r($product);
				?><br>
				<?}?>
			  </td>

            </tr>
			<?}}else{?>
			<tr> <td colspan="<?php echo ($numcols +1);?>"> Sem pedidos nesse dia</td>
			<?}?>
			 <tr class="filter">
			 <td> <strong>Número de Pratos do dia</strong></td>
       <td class="center"><strong>Quantidade</strong></td>
       <td class="center"><strong>Localização</strong></td>
			 </tr>
			 <?php if(isset($tsagawa)){foreach ($tsagawa as $psagawa){?>
			 <tr>
         <td class="left" style="width:33%;"><?php echo $psagawa['name'];?></td>
         <td class="center" style="width:10%;"><?php echo $psagawa['qtdgeral'];?></td>
         <td class="center" style="width:43%;"><?php echo $psagawa['location'];?></td>

				<!-- <td class="left" style="width:33%;"><?php echo  $psagawa['name'];?></td>
        <!-- <td class="left"><?php echo $product['location'];?></td> -->
      	<!-- <td class="center" style="width:10%;"><?php echo $psagawa['qtdgeral'];?></td>
        <td class="center" style="width:43%;"><?php echo '<input class="autosave" id="pr-'.$psagawa['product_id'].'" value="'.$psagawa['location'].'" />';?></td> -->

			 </tr>
			 <?php }} ?>
          </tbody>
	  </table><br/><br/><?php }?>

     <?php if($filter_tipo=="lm" or $filter_tipo==""){?>
	  <table class="list">
          <thead>
		  <tr >
			<td class="left" colspan="<?php echo $numcols;?>"><strong>Local Manhã</strong> - <?php echo "de ".$filter_delivery_date." a ".$filter_delivery_date2; ?> - <?php echo $filtrostatus; ?></td>
		  </tr>
            <tr>
             <td class="right">Pedido n°</td>

              <td class="left">Hora</td>

              <td class="left" width="100%">Produtos</td>


            </tr>
          </thead>
          <tbody>

			<?php if(isset($lmanha)){foreach ($lmanha as $manha){?>
            <tr>

              <td class="right" ><a href="index.php?route=sale/order/info&order_id=<?php echo $manha['order_id'];?>&token=<?php echo $_SESSION['token'];?>"><?php echo $manha['order_id'];?></a></td>
              <td class="left" ><?php echo $manha['ddw_time_slot'];?></td>
              <td class="left" >
				<?php foreach ($manha['products'] as $product) { ?>
				<?php echo "-".$product['quantity']." - ".$product['name'];
				//print_r($product);
				?><br>
				<?}?>
			  </td>

            </tr>
			<?}}else{?>
			<tr> <td colspan="<?php echo $numcols;?>"> Sem pedidos nesse dia</td>
			<?}?>
			 <tr class="filter">
			 <td colspan="<?php echo $numcols;?>" > <strong>Número de Pratos do dia</strong></td>
			 </tr>
			 <?php if(isset($tlmanha)){foreach ($tlmanha as $ptlmanha){?>
			 <tr>
				<td class="right" colspan="<?php echo ($numcols-1);?>"><?php echo $ptlmanha['name'];?></td>
				<td class="left"><?php echo $ptlmanha['qtdgeral'];?></td>

			 </tr>
			 <?php }} ?>
          </tbody>
	 </table><br/><br/><?php }?>


		<?php if($filter_tipo=="ln" or $filter_tipo==""){?>
		<table class="list">
          <thead>
		  <tr >
			<td class="left" colspan="<?php echo $numcols;?>"><strong>Local Noite</strong> - <?php echo "de ".$filter_delivery_date." a ".$filter_delivery_date2; ?> - <?php echo $filtrostatus; ?></td>
		  </tr>
            <tr>
             <td class="right">Pedido n°</td>

              <td class="left">Hora</td>

              <td class="left"  width="50%">Produtos</td>


            </tr>
          </thead>
          <tbody>

			<?php if(isset($lnoite)){foreach ($lnoite as $noite){?>
            <tr>

              <td class="right" ><a href="index.php?route=sale/order/info&order_id=<?php echo $noite['order_id'];?>&token=<?php echo $_SESSION['token'];?>"><?php echo $noite['order_id'];?></a></td>
              <td class="left" ><?php echo $noite['ddw_time_slot'];?></td>
              <td class="left" >
				<?php foreach ($noite['products'] as $product) { ?>
				<?php echo "-".$product['quantity']." - ".$product['name'];
				//print_r($product);
				?><br>
				<?}?>
			  </td>

            </tr>
			<?}}else{?>
			<tr> <td colspan="<?php echo $numcols;?>"> Sem pedidos nesse dia</td>
			<?}?>
			 <tr class="filter">
			 <td colspan="<?php echo $numcols;?>" > <strong>Número de Pratos do dia</strong></td>
			 </tr>
			 <?php if(isset($tlnoite)){foreach ($tlnoite as $ptlnoite){?>
			 <tr>
				<td class="right" colspan="<?php echo ($numcols-1);?>"><?php echo $ptlnoite['name'];?></td>
				<td class="left"><?php echo $ptlnoite['qtdgeral'];?></td>

			 </tr>
			 <?php }} ?>
          </tbody>
        </table><br/><br/>
		<?php }?>


		<?php if($filter_tipo=="bl" or $filter_tipo==""){?>
		<table class="list">
          <thead>
		  <tr >

        <td class="left" colspan="<?php echo ($numcols+1);?>"><strong>Retirar na Loja</strong> - <?php echo "de ".$filter_delivery_date." a ".$filter_delivery_date2; ?> - <?php echo $filtrostatus; ?> </td>
  		  </tr>
              <tr>
               <td class="center" style="width:20%;" >Pedido n°</td>

                <td class="left" style="width:20%;" >Hora</td>

                <td class="left" style="width:60%;" colspan="2">Produtos</td>


              </tr>

          </thead>
          <tbody>

			<?php if(isset($lretirar)){foreach ($lretirar as $retirar){?>
            <tr>

              <td class="right" ><a href="index.php?route=sale/order/info&order_id=<?php echo $retirar['order_id'];?>&token=<?php echo $_SESSION['token'];?>"><?php echo $retirar['order_id'];?></a></td>
              <td class="left" ><?php echo $retirar['ddw_time_slot'];?></td>
              <td class="left" colspan="2">
				<?php foreach ($retirar['products'] as $product) { ?>
				<?php echo "-".$product['quantity']." - ".$product['name'];
				//print_r($product);
				?><br>
				<?}?>
			  </td>

            </tr>
			<?}}else{?>
			<tr> <td colspan="<?php echo ($numcols +1);?>"> Sem pedidos nesse dia</td>
			<?}?>
			 <tr class="filter">
         <td> <strong>Número de Pratos do dia</strong></td>
         <td class="center"><strong>Quantidade</strong></td>
         <td class="center"><strong>Localização</strong></td>
			 </tr>
			 <?php if(isset($tlretirar)){foreach ($tlretirar as $ptlretirar){?>
			 <tr>
        <td class="left" style="width:33%;"><?php echo $ptlretirar['name'];?></td>
        <td class="center" style="width:10%;"><?php echo $ptlretirar['qtdgeral'];?></td>
        <td class="center" style="width:43%;"><?php echo $ptlretirar['location'];?></td>

		 </tr>
			 <?php }} ?>
          </tbody>
        </table><br/><br/>

		<?php }?>




    </div>
  </div>
  </form>
</div>
<script type="text/javascript"><!--

function imprimir_tudo(){
	var conteudo = document.getElementById('imprimivel').innerHTML;
	conteudo = conteudo.replace(/<table/gi, "<table border='1' cellpadding='0' cellspacing='0' style='font-family: Verdana, Geneva, Arial, Helvetica, sans-serif;border-width: 1px;'");
	tela_impressao = window.open('about:blank');
	tela_impressao.document.write(conteudo);
	tela_impressao.window.print();
	tela_impressao.window.close();

}
 </script>

<script type="text/javascript"><!--
function exportar(){
  var Html = " <table border='1' > <tr> <th>Nome Produto</th> <th>Quantidade</th> <th>Localização</th> </tr>";




  alert(Html);


}
</script>
<script type="text/javascript"><!--
$(document).ready(function() {
	$('.date').datepicker({dateFormat: 'yy-mm-dd'});


});
//--></script>
<script type="text/javascript"><!--
$('#form input').keydown(function(e) {
	if (e.keyCode == 13) {
		filter();
	}
});
//--></script>
<script type="text/javascript"><!--
function filter(exportar) {


console.log(exportar);
  if(exportar == ""){
   var route="sale/relatorio";
 }else {
   var route="sale/relatorio/exportarxls";
 }

  url = 'index.php?route='+route+'&token=<?php echo $_SESSION['token'];?>';

	var filter_order_status_id = $('select[name=\'filter_order_status_id\']').attr('value');

	if (filter_order_status_id != '*') {
		url += '&filter_order_status_id=' + encodeURIComponent(filter_order_status_id);
	}

	var filter_tipo = $('select[name=\'filter_tipo\']').attr('value');

	if (filter_tipo != '*') {
		url += '&filter_tipo=' + encodeURIComponent(filter_tipo);
	}

	var filter_delivery_date = $('input[name=\'filter_delivery_date\']').attr('value');

	if (filter_delivery_date) {
		url += '&filter_delivery_date=' + encodeURIComponent(filter_delivery_date);
	}
	var filter_delivery_date2 = $('input[name=\'filter_delivery_date2\']').attr('value');

	if (filter_delivery_date2) {
		url += '&filter_delivery_date2=' + encodeURIComponent(filter_delivery_date2);
	}


	location = url;
}
//--></script>
<?php echo $footer; ?>
