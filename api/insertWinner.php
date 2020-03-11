<?php
error_reporting(E_ALL);
ini_set('display_errors', 0);
require_once '../includes/main.php';
header('Content-type: application/json');

if($_POST['winid']):
		$res = insert_winner_user($_POST['rid'],$_POST['winid']);
	
		echo json_encode($res,JSON_HEX_QUOT | JSON_HEX_TAG);
	
else:
		$result['sonuc']="0";
		$result['durum']="Bir hata oluştu lütfen tekrar deneyin.";
		echo json_encode($result,JSON_HEX_QUOT | JSON_HEX_TAG);
endif;