<?php
include "config.php";
date_default_timezone_set('Asia/Tokyo');

//$conexao = mysqli_connect(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE) or die("<br><br><font size=6>Manutenção do banco de dados... por favor, volte mais tarde </font>");

$hoje = date('Y-m-d');



$mysqli = new mysqli(DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
if ($mysqli->connect_errno) {
    printf("Connect failed: %s\n", $mysqli->connect_error);
    exit();
}



$buscaproduto = "SELECT  * from ".DB_PREFIX."setting WHERE `key`= 'config_email' and store_id=0";
//echo $buscaproduto."BBBBBBBBBBBBB";
$query = $mysqli->query($buscaproduto);
$busca=  mysqli_fetch_assoc($query);


$mailaviso=$busca[value];
$headers  = 'MIME-Version: 1.0' . "\r\n";
$headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";
$headers .= 'From: '.$mailaviso. "\r\n" . 	'Reply-To: '.$mailaviso . "\r\n" .	'X-Mailer: PHP/' . phpversion();


//1,2,3,5,7,10,15,30
$hoje = date('Y-m-d');

$busca_stock = "select *  from " . DB_PREFIX . "product_stock ps where 1 ";
$busca_stock .= " AND '$hoje'>=  ps.lot_exp and stock_qty>0 order by stock_product_id";
$busca = $mysqli->query($busca_stock);
$mensagem="Produtos Vencidos:
<table border=1 cellspacing=0><tr><td>Produto</td><td>QTD</td><td>Data do Lote</td><td>Vencimento do lote</td></tr>";
$conta=0;
while($produto = mysqli_fetch_assoc($busca)){
	$conta++;
  $buscaproduto = "select name  from " . DB_PREFIX . "product_description  where product_id='".$produto[stock_product_id]."' ";
  $query = $mysqli->query($buscaproduto);
  $produton=  mysqli_fetch_assoc($query);

  $mensagem.= "<tr><td>".$produton[name]."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
}
if($conta==0){$mensagem.="<tr><td colspan=4>Nenhum lote nesse vencimento</td></tr>";}
$mensagem.="</table><br><br>";


$busca_stock = "select *  from " . DB_PREFIX . "product_stock ps where 1 ";
$busca_stock .= " AND '$hoje'>=  DATE_SUB(ps.lot_exp ,INTERVAL 1 DAY)  and '$hoje'<=  ps.lot_exp and stock_qty>0 order by stock_product_id";
$busca = $mysqli->query($busca_stock);
$mensagem.="Produtos com vencimento em 1 dia ou hoje:
<table border=1 cellspacing=0><tr><td>Produto</td><td>QTD</td><td>Data do Lote</td><td>Vencimento do lote</td></tr>";
$conta=0;
while($produto = mysqli_fetch_assoc($busca)){
  $buscaproduto = "select name  from " . DB_PREFIX . "product_description  where product_id='".$produto[stock_product_id]."' ";
  $query = $mysqli->query($buscaproduto);
  $produton=  mysqli_fetch_assoc($query);

  $mensagem.= "<tr><td>".$produton[name]."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
}
if($conta==0){$mensagem.="<tr><td colspan=4>Nenhum lote nesse vencimento</td></tr>";}
$mensagem.="</table><br><br>";



$busca_stock = "select *  from " . DB_PREFIX . "product_stock ps where 1 ";
$busca_stock .= " AND '$hoje'>=  DATE_SUB(ps.lot_exp ,INTERVAL 2 DAY) AND '$hoje'<=  DATE_SUB(ps.lot_exp ,INTERVAL 1 DAY) and stock_qty>0 order by stock_product_id";
$busca = $mysqli->query($busca_stock);
$mensagem.="Produtos com vencimento em 2 dias:
<table border=1 cellspacing=0><tr><td>Produto</td><td>QTD</td><td>Data do Lote</td><td>Vencimento do lote</td></tr>";
$conta=0;
while($produto = mysqli_fetch_assoc($busca)){
  $buscaproduto = "select name  from " . DB_PREFIX . "product_description  where product_id='".$produto[stock_product_id]."' ";
  $query = $mysqli->query($buscaproduto);
  $produton=  mysqli_fetch_assoc($query);

  $mensagem.= "<tr><td>".$produton[name]."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
}
if($conta==0){$mensagem.="<tr><td colspan=4>Nenhum lote nesse vencimento</td></tr>";}
$mensagem.="</table><br><br>";




$busca_stock = "select *  from " . DB_PREFIX . "product_stock ps where 1 ";
$busca_stock .= " AND '$hoje'>=  DATE_SUB(ps.lot_exp ,INTERVAL 3 DAY) AND '$hoje'<=  DATE_SUB(ps.lot_exp ,INTERVAL 2 DAY) and stock_qty>0 order by stock_product_id";
$busca = $mysqli->query($busca_stock);
$mensagem.="Produtos com vencimento em 3 dias:
<table border=1 cellspacing=0><tr><td>Produto</td><td>QTD</td><td>Data do Lote</td><td>Vencimento do lote</td></tr>";
$conta=0;
while($produto = mysqli_fetch_assoc($busca)){
  $buscaproduto = "select name  from " . DB_PREFIX . "product_description  where product_id='".$produto[stock_product_id]."' ";
  $query = $mysqli->query($buscaproduto);
  $produton=  mysqli_fetch_assoc($query);

  $mensagem.= "<tr><td>".$produton[name]."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
}
if($conta==0){$mensagem.="<tr><td colspan=4>Nenhum lote nesse vencimento</td></tr>";}
$mensagem.="</table><br><br>";


$busca_stock = "select *  from " . DB_PREFIX . "product_stock ps where 1 ";
$busca_stock .= " AND '$hoje'>=  DATE_SUB(ps.lot_exp ,INTERVAL 5 DAY) AND '$hoje'<=  DATE_SUB(ps.lot_exp ,INTERVAL 3 DAY) and stock_qty>0 order by stock_product_id";
$busca = $mysqli->query($busca_stock);
$mensagem.="Produtos com vencimento entre 3 e 5 dias:
<table border=1 cellspacing=0><tr><td>Produto</td><td>QTD</td><td>Data do Lote</td><td>Vencimento do lote</td></tr>";
$conta=0;
while($produto = mysqli_fetch_assoc($busca)){
  $buscaproduto = "select name  from " . DB_PREFIX . "product_description  where product_id='".$produto[stock_product_id]."' ";
  $query = $mysqli->query($buscaproduto);
  $produton=  mysqli_fetch_assoc($query);

  $mensagem.= "<tr><td>".$produton[name]."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
}
if($conta==0){$mensagem.="<tr><td colspan=4>Nenhum lote nesse vencimento</td></tr>";}
$mensagem.="</table><br><br>";



$busca_stock = "select *  from " . DB_PREFIX . "product_stock ps where 1 ";
$busca_stock .= " AND '$hoje'>=  DATE_SUB(ps.lot_exp ,INTERVAL 7 DAY) AND '$hoje'<=  DATE_SUB(ps.lot_exp ,INTERVAL 5 DAY) and stock_qty>0 order by stock_product_id";
$busca = $mysqli->query($busca_stock);
$mensagem.="Produtos com vencimento entre 5 e 7 dias:
<table border=1 cellspacing=0><tr><td>Produto</td><td>QTD</td><td>Data do Lote</td><td>Vencimento do lote</td></tr>";
$conta=0;
while($produto = mysqli_fetch_assoc($busca)){
  $buscaproduto = "select name  from " . DB_PREFIX . "product_description  where product_id='".$produto[stock_product_id]."' ";
  $query = $mysqli->query($buscaproduto);
  $produton=  mysqli_fetch_assoc($query);

  $mensagem.= "<tr><td>".$produton[name]."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
}
if($conta==0){$mensagem.="<tr><td colspan=4>Nenhum lote nesse vencimento</td></tr>";}
$mensagem.="</table><br><br>";



$busca_stock = "select *  from " . DB_PREFIX . "product_stock ps where 1 ";
$busca_stock .= " AND '$hoje'>=  DATE_SUB(ps.lot_exp ,INTERVAL 10 DAY) AND '$hoje'<=  DATE_SUB(ps.lot_exp ,INTERVAL 7 DAY) and stock_qty>0 order by stock_product_id";
$busca = $mysqli->query($busca_stock);
$mensagem.="Produtos com vencimento entre 7  e 10 dias:
<table border=1 cellspacing=0><tr><td>Produto</td><td>QTD</td><td>Data do Lote</td><td>Vencimento do lote</td></tr>";
$conta=0;
while($produto = mysqli_fetch_assoc($busca)){
  $buscaproduto = "select name  from " . DB_PREFIX . "product_description  where product_id='".$produto[stock_product_id]."' ";
  $query = $mysqli->query($buscaproduto);
  $produton=  mysqli_fetch_assoc($query);

  $mensagem.= "<tr><td>".$produton[name]."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
}
if($conta==0){$mensagem.="<tr><td colspan=4>Nenhum lote nesse vencimento</td></tr>";}
$mensagem.="</table><br><br>";




$busca_stock = "select *  from " . DB_PREFIX . "product_stock ps where 1 ";
$busca_stock .= " AND '$hoje'>=  DATE_SUB(ps.lot_exp ,INTERVAL 15 DAY) AND '$hoje'<=  DATE_SUB(ps.lot_exp ,INTERVAL 10 DAY) and stock_qty>0 order by stock_product_id";
$busca = $mysqli->query($busca_stock);
$mensagem.="Produtos com vencimento entre 10 e 15 dias:
<table border=1 cellspacing=0><tr><td>Produto</td><td>QTD</td><td>Data do Lote</td><td>Vencimento do lote</td></tr>";
$conta=0;
while($produto = mysqli_fetch_assoc($busca)){
  $buscaproduto = "select name  from " . DB_PREFIX . "product_description  where product_id='".$produto[stock_product_id]."' ";
  $query = $mysqli->query($buscaproduto);
  $produton=  mysqli_fetch_assoc($query);

  $mensagem.= "<tr><td>".$produton[name]."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
}
if($conta==0){$mensagem.="<tr><td colspan=4>Nenhum lote nesse vencimento</td></tr>";}
$mensagem.="</table><br><br>";




$busca_stock = "select *  from " . DB_PREFIX . "product_stock ps where 1 ";
$busca_stock .= " AND '$hoje'>=  DATE_SUB(ps.lot_exp ,INTERVAL 20 DAY) AND '$hoje'<=  DATE_SUB(ps.lot_exp ,INTERVAL 15 DAY) and stock_qty>0 order by stock_product_id";
$busca = $mysqli->query($busca_stock);
$mensagem.="Produtos com vencimento entre 15 e 20 dias:
<table border=1 cellspacing=0><tr><td>Produto</td><td>QTD</td><td>Data do Lote</td><td>Vencimento do lote</td></tr>";
$conta=0;
while($produto = mysqli_fetch_assoc($busca)){
  $buscaproduto = "select name  from " . DB_PREFIX . "product_description  where product_id='".$produto[stock_product_id]."' ";
  $query = $mysqli->query($buscaproduto);
  $produton=  mysqli_fetch_assoc($query);

  $mensagem.= "<tr><td>".$produton[name]."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
}
if($conta==0){$mensagem.="<tr><td colspan=4>Nenhum lote nesse vencimento</td></tr>";}
$mensagem.="</table><br><br>";



$busca_stock = "select *  from " . DB_PREFIX . "product_stock ps where 1 ";
$busca_stock .= " AND '$hoje'>=  DATE_SUB(ps.lot_exp ,INTERVAL 30 DAY) AND '$hoje'<=  DATE_SUB(ps.lot_exp ,INTERVAL 20 DAY) and stock_qty>0 order by stock_product_id";
$busca = $mysqli->query($busca_stock);
$mensagem.="Produtos com vencimento entre 20 e 30 dias:
<table border=1 cellspacing=0><tr><td>Produto</td><td>QTD</td><td>Data do Lote</td><td>Vencimento do lote</td></tr>";
$conta=0;
while($produto = mysqli_fetch_assoc($busca)){
  $buscaproduto = "select name  from " . DB_PREFIX . "product_description  where product_id='".$produto[stock_product_id]."' ";
  $query = $mysqli->query($buscaproduto);
  $produton=  mysqli_fetch_assoc($query);

  $mensagem.= "<tr><td>".$produton[name]."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
}
if($conta==0){$mensagem.="<tr><td colspan=4>Nenhum lote nesse vencimento</td></tr>";}
$mensagem.="</table><br><br>";




$busca_stock = "select *  from " . DB_PREFIX . "product_stock ps where 1 ";
$busca_stock .= " AND '$hoje'>=  DATE_SUB(ps.lot_exp ,INTERVAL 40 DAY) AND '$hoje'<=  DATE_SUB(ps.lot_exp ,INTERVAL 30 DAY) and stock_qty>0 order by stock_product_id";
$busca = $mysqli->query($busca_stock);
$mensagem.="Produtos com vencimento entre 30 e 40 dias:
<table border=1 cellspacing=0><tr><td>Produto</td><td>QTD</td><td>Data do Lote</td><td>Vencimento do lote</td></tr>";
$conta=0;
while($produto = mysqli_fetch_assoc($busca)){
  $buscaproduto = "select name  from " . DB_PREFIX . "product_description  where product_id='".$produto[stock_product_id]."' ";
  $query = $mysqli->query($buscaproduto);
  $produton=  mysqli_fetch_assoc($query);

  $mensagem.= "<tr><td>".$produton[name]."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
}
if($conta==0){$mensagem.="<tr><td colspan=4>Nenhum lote nesse vencimento</td></tr>";}
$mensagem.="</table><br><br>";


$busca_stock = "select *  from " . DB_PREFIX . "product_stock ps where 1 ";
$busca_stock .= " AND '$hoje'>=  DATE_SUB(ps.lot_exp ,INTERVAL 50 DAY) AND '$hoje'<=  DATE_SUB(ps.lot_exp ,INTERVAL 40 DAY) and stock_qty>0 order by stock_product_id";
$busca = $mysqli->query($busca_stock);
$mensagem.="Produtos com vencimento entre 40 e 50 dias:
<table border=1 cellspacing=0><tr><td>Produto</td><td>QTD</td><td>Data do Lote</td><td>Vencimento do lote</td></tr>";
$conta=0;
while($produto = mysqli_fetch_assoc($busca)){
  $buscaproduto = "select name  from " . DB_PREFIX . "product_description  where product_id='".$produto[stock_product_id]."' ";
  $query = $mysqli->query($buscaproduto);
  $produton=  mysqli_fetch_assoc($query);

  $mensagem.= "<tr><td>".$produton[name]."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
}
if($conta==0){$mensagem.="<tr><td colspan=4>Nenhum lote nesse vencimento</td></tr>";}
$mensagem.="</table><br><br>";



$busca_stock = "select *  from " . DB_PREFIX . "product_stock ps where 1 ";
$busca_stock .= " AND '$hoje'>=  DATE_SUB(ps.lot_exp ,INTERVAL 60 DAY) AND '$hoje'<=  DATE_SUB(ps.lot_exp ,INTERVAL 50 DAY) and stock_qty>0 order by stock_product_id";
$busca = $mysqli->query($busca_stock);
$mensagem.="Produtos com vencimento entre 50 e 60 dias:
<table border=1 cellspacing=0><tr><td>Produto</td><td>QTD</td><td>Data do Lote</td><td>Vencimento do lote</td></tr>";
$conta=0;
while($produto = mysqli_fetch_assoc($busca)){
  $buscaproduto = "select name  from " . DB_PREFIX . "product_description  where product_id='".$produto[stock_product_id]."' ";
  $query = $mysqli->query($buscaproduto);
  $produton=  mysqli_fetch_assoc($query);

  $mensagem.= "<tr><td>".$produton[name]."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
}
if($conta==0){$mensagem.="<tr><td colspan=4>Nenhum lote nesse vencimento</td></tr>";}
$mensagem.="</table><br><br>";


$busca_stock = "select *  from " . DB_PREFIX . "product_stock ps where 1 ";
$busca_stock .= " AND '$hoje'>=  DATE_SUB(ps.lot_exp ,INTERVAL 70 DAY) AND '$hoje'<=  DATE_SUB(ps.lot_exp ,INTERVAL 60 DAY) and stock_qty>0 order by stock_product_id";
$busca = $mysqli->query($busca_stock);
$mensagem.="Produtos com vencimento entre 60 e 70 dias:
<table border=1 cellspacing=0><tr><td>Produto</td><td>QTD</td><td>Data do Lote</td><td>Vencimento do lote</td></tr>";
$conta=0; 
while($produto = mysqli_fetch_assoc($busca)){
  $buscaproduto = "select name  from " . DB_PREFIX . "product_description  where product_id='".$produto[stock_product_id]."' ";
  $query = $mysqli->query($buscaproduto);
  $produton=  mysqli_fetch_assoc($query);

  $mensagem.= "<tr><td>".$produton[name]."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
}
if($conta==0){$mensagem.="<tr><td colspan=4>Nenhum lote nesse vencimento</td></tr>";}
$mensagem.="</table><br><br>";
//echo $mensagem."done...";
echo "<a href='#null' onclick='self.print();'>[IMPRIMIR]</a><br><br>".$mensagem;
?>