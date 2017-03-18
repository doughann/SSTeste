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

$busca_stock = "select name, product_id from " . DB_PREFIX . "product_description";
$busca = $mysqli->query($busca_stock);
while($produto = mysqli_fetch_assoc($busca)){
	$busca_produto="select tipo from " . DB_PREFIX . "product where '$produto[product_id]'=product_id";
	$busca_qty = $mysqli->query($busca_produto);

	$produtoT = mysqli_fetch_assoc($busca_qty);
	if($produtoT[tipo]=="0"){
		$pos1 = stripos($produto[name], "&quot;P&quot;");
		$pos2 = stripos($produto[name], "&quot;G&quot;");
		$pos3 = stripos($produto[name], "&quot;Mistura&quot;");
		if($pos1>1){
			$altera = "update " . DB_PREFIX . "product set tipo='2' where product_id='$produto[product_id]'";
			$mysqli->query($altera);
		}
		if($pos2>1){
			$altera = "update " . DB_PREFIX . "product set tipo='1' where product_id='$produto[product_id]'";
			$mysqli->query($altera);
		}
		if($pos3>1){
			$altera = "update " . DB_PREFIX . "product set tipo='3' where product_id='$produto[product_id]'";
			$mysqli->query($altera);
		}
		
	}
	
}
echo "done...";
?>