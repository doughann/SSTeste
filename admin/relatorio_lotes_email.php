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


$mailaviso=$busca['value'];
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

  $mensagem.= "<tr><td>".html_entity_decode($produton[name], ENT_QUOTES, 'ISO-8859-1')."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
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

  $mensagem.= "<tr><td>".html_entity_decode($produton[name], ENT_QUOTES, 'ISO-8859-1')."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
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

  $mensagem.= "<tr><td>".html_entity_decode($produton[name], ENT_QUOTES, 'ISO-8859-1')."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
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

  $mensagem.= "<tr><td>".html_entity_decode($produton[name], ENT_QUOTES, 'ISO-8859-1')."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
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

  $mensagem.= "<tr><td>".html_entity_decode($produton[name], ENT_QUOTES, 'ISO-8859-1')."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
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

  $mensagem.= "<tr><td>".html_entity_decode($produton[name], ENT_QUOTES, 'ISO-8859-1')."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
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

  $mensagem.= "<tr><td>".html_entity_decode($produton[name], ENT_QUOTES, 'ISO-8859-1')."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
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

  $mensagem.= "<tr><td>".html_entity_decode($produton[name], ENT_QUOTES, 'ISO-8859-1')."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
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

  $mensagem.= "<tr><td>".html_entity_decode($produton[name], ENT_QUOTES, 'ISO-8859-1')."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
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

  $mensagem.= "<tr><td>".html_entity_decode($produton[name], ENT_QUOTES, 'ISO-8859-1')."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
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

  $mensagem.= "<tr><td>".html_entity_decode($produton[name], ENT_QUOTES, 'ISO-8859-1')."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
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

  $mensagem.= "<tr><td>".html_entity_decode($produton[name], ENT_QUOTES, 'ISO-8859-1')."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
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

  $mensagem.= "<tr><td>".html_entity_decode($produton[name], ENT_QUOTES, 'ISO-8859-1')."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
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

  $mensagem.= "<tr><td>".html_entity_decode($produton[name], ENT_QUOTES, 'ISO-8859-1')."</td><td>".$produto[stock_qty]."</td><td>".$produto[lot_date]."</td><td>".$produto[lot_exp]."</td></tr>";
	
}
if($conta==0){$mensagem.="<tr><td colspan=4>Nenhum lote nesse vencimento</td></tr>";}
$mensagem.="</table><br><br>";
//echo $mensagem."done...";
//echo "<a href='#null' onclick='self.print();'>[IMPRIMIR]</a><br><br>".$mensagem;

$qalerta="Relatorio de vencimento de lotes";












// Version
define('VERSION', '1.5.6.4');

// Configuration
if (file_exists('config.php')) {
	require_once('config.php');
}

// Install
if (!defined('DIR_APPLICATION')) {
	header('Location: ../install/index.php');
	exit;
}

//VirtualQMOD
require_once('../vqmod/vqmod.php');
VQMod::bootup();

// VQMODDED Startup
require_once(VQMod::modCheck(DIR_SYSTEM . 'startup.php'));

// Application Classes
require_once(VQMod::modCheck(DIR_SYSTEM . 'library/currency.php'));
require_once(VQMod::modCheck(DIR_SYSTEM . 'library/user.php'));
require_once(VQMod::modCheck(DIR_SYSTEM . 'library/weight.php'));
require_once(VQMod::modCheck(DIR_SYSTEM . 'library/length.php'));

// Registry
$registry = new Registry();

// Loader
$loader = new Loader($registry);
$registry->set('load', $loader);

// Config
$config = new Config();
$registry->set('config', $config);

// Database
$db = new DB(DB_DRIVER, DB_HOSTNAME, DB_USERNAME, DB_PASSWORD, DB_DATABASE);
$registry->set('db', $db);

// Settings
$query = $db->query("SELECT * FROM " . DB_PREFIX . "setting WHERE store_id = '0'");

foreach ($query->rows as $setting) {
	if (!$setting['serialized']) {
		$config->set($setting['key'], $setting['value']);
	} else {
		$config->set($setting['key'], unserialize($setting['value']));
	}
}

// Url
$url = new Url(HTTP_SERVER, $config->get('config_secure') ? HTTPS_SERVER : HTTP_SERVER);	
$registry->set('url', $url);

// Log
$log = new Log($config->get('config_error_filename'));
$registry->set('log', $log);

function error_handler($errno, $errstr, $errfile, $errline) {
	global $log, $config;
	
	switch ($errno) {
		case E_NOTICE:
		case E_USER_NOTICE:
			$error = 'Notice';
			break;
		case E_WARNING:
		case E_USER_WARNING:
			$error = 'Warning';
			break;
		case E_ERROR:
		case E_USER_ERROR:
			$error = 'Fatal Error';
			break;
		default:
			$error = 'Unknown';
			break;
	}
		
	if ($config->get('config_error_display')) {
		echo '<b>' . $error . '</b>: ' . $errstr . ' in <b>' . $errfile . '</b> on line <b>' . $errline . '</b>';
	}
	
	if ($config->get('config_error_log')) {
		$log->write('PHP ' . $error . ':  ' . $errstr . ' in ' . $errfile . ' on line ' . $errline);
	}

	return true;
}

// Error Handler
set_error_handler('error_handler');

// Request
$request = new Request();
$registry->set('request', $request);

// Response
$response = new Response();
$response->addHeader('Content-Type: text/html; charset=utf-8');
$registry->set('response', $response); 

// Cache
$cache = new Cache();
$registry->set('cache', $cache); 

// Session
$session = new Session();
$registry->set('session', $session); 

// Language
$languages = array();

$query = $db->query("SELECT * FROM `" . DB_PREFIX . "language`"); 

foreach ($query->rows as $result) {
	$languages[$result['code']] = $result;
}

$config->set('config_language_id', $languages[$config->get('config_admin_language')]['language_id']);

// Language	
$language = new Language($languages[$config->get('config_admin_language')]['directory']);
$language->load($languages[$config->get('config_admin_language')]['filename']);	
$registry->set('language', $language);

// Document
$registry->set('document', new Document()); 		

// Currency
$registry->set('currency', new Currency($registry));		

// Weight
$registry->set('weight', new Weight($registry));

// Length
$registry->set('length', new Length($registry));

// User
$registry->set('user', new User($registry));

//OpenBay Pro
$registry->set('openbay', new Openbay($registry));

// Front Controller
$controller = new Front($registry);

// Login
$controller->addPreAction(new Action('common/home/login'));

// Permission
$controller->addPreAction(new Action('common/home/permission'));








$buscaproduto = "SELECT  * from ".DB_PREFIX."setting WHERE `key`= 'config_mail_protocol' and store_id=0";
$query = $mysqli->query($buscaproduto);
$busca=  mysqli_fetch_assoc($query);
$config_mail_protocol=$busca['value'];

$buscaproduto = "SELECT  * from ".DB_PREFIX."setting WHERE `key`= 'config_mail_parameter' and store_id=0";
$query = $mysqli->query($buscaproduto);
$busca=  mysqli_fetch_assoc($query);
$config_mail_parameter=$busca['value'];


$buscaproduto = "SELECT  * from ".DB_PREFIX."setting WHERE `key`= 'config_smtp_host' and store_id=0";
$query = $mysqli->query($buscaproduto);
$busca=  mysqli_fetch_assoc($query);
$config_smtp_host=$busca['value'];


$buscaproduto = "SELECT  * from ".DB_PREFIX."setting WHERE `key`= 'config_smtp_username' and store_id=0";
$query = $mysqli->query($buscaproduto);
$busca=  mysqli_fetch_assoc($query);
$config_smtp_username=$busca['value'];

$buscaproduto = "SELECT  * from ".DB_PREFIX."setting WHERE `key`= 'config_smtp_password' and store_id=0";
$query = $mysqli->query($buscaproduto);
$busca=  mysqli_fetch_assoc($query);
$config_smtp_password=$busca['value'];

$buscaproduto = "SELECT  * from ".DB_PREFIX."setting WHERE `key`= 'config_smtp_port' and store_id=0";
$query = $mysqli->query($buscaproduto);
$busca=  mysqli_fetch_assoc($query);
$config_smtp_port=$busca['value'];

$buscaproduto = "SELECT  * from ".DB_PREFIX."setting WHERE `key`= 'config_smtp_timeout' and store_id=0";
$query = $mysqli->query($buscaproduto);
$busca=  mysqli_fetch_assoc($query);
$config_smtp_timeout=$busca['value'];


$buscaproduto = "SELECT  * from ".DB_PREFIX."setting WHERE `key`= 'config_alert_emails' and store_id=0";
$query = $mysqli->query($buscaproduto);
$busca=  mysqli_fetch_assoc($query);
$config_alert_emails=$busca['value'];



				$mail = new Mail(); 
				$mail->protocol = $config_mail_protocol;
				$mail->parameter = $config_mail_parameter;
				$mail->hostname = $config_smtp_host;
				$mail->username = $config_smtp_username;
				$mail->password = $config_smtp_password;
				$mail->port = $config_smtp_port;
				$mail->timeout = $config_smtp_timeout;
				$mail->setTo($mailaviso);
				$mail->setFrom($mailaviso);
				$mail->setSender("Saude e Sabor");
				$mail->setSubject(html_entity_decode($qalerta, ENT_QUOTES, 'UTF-8'));
				$mail->setHtml($mensagem);
				$mail->send();

				// Send to additional alert emails
				$emails = explode(',', $config_alert_emails);

				foreach ($emails as $email) {
					if ($email && preg_match('/^[^\@]+@.*\.[a-z]{2,6}$/i', $email)) {
						$mail->setTo($email);
						$mail->send();
					}
				}
				//$mail->setSubject(html_entity_decode($subject." - Cozinha" , ENT_QUOTES, 'UTF-8'));
				//$mail->setTo($this->config->get('config_email_cozinha'));//email da cozinha
				//$mail->send();//envia email da cozinha




//mail ( $mailaviso, $qalerta , $mensagem , $headers , "-f".$mailaviso );
//mail ( "wagner.felix@gmail.com", $qalerta , $mensagem , $headers , "-f".$mailaviso );
//mail ( "webmaster@fabrica10.com.br", $qalerta , $mensagem , $headers , "-f".$mailaviso );
//mail ( "webmaster@route10.com.br", $qalerta , $mensagem , $headers , "-f".$mailaviso );
?>