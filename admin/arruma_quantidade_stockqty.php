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

$busca_stock = "select quantity, product_id, expire from " . DB_PREFIX . "product";
$busca = $mysqli->query($busca_stock);
while($produto = mysqli_fetch_assoc($busca)){
	$busca_stock="select sum(stock_qty) as qtd from " . DB_PREFIX . "product_stock where '$produto[product_id]'=stock_product_id group by stock_product_id";
	$busca_qty = $mysqli->query($busca_stock);

	$stock_qtd = mysqli_fetch_assoc($busca_qty);
	//if($stock_qtd[qtd]>$produto[quantity]){
		$alterar = "update " . DB_PREFIX . "product set quantity='$stock_qtd[qtd]' where product_id='$produto[product_id]'";
		$altera = $mysqli->query($alterar);
		echo "HUM ".$stock_qtd[qtd]."-".$produto[quantity]."<br>";
	//}
	
}
echo "done...";
?>