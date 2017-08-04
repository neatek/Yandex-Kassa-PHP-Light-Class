<?php
require_once dirname(__FILE__).'/YandexKassa.class.php';
/*$settings = array(
	'logs_folder' => '/var/www/html/logs/'
);
$ya = new YandexMoney($settings);*/
$ya = new YandexMoney();
if($_GET['act'] == 'checkOrder') {
	$ya->checkOrder();
}
else {
	$answer = $ya->paymentAviso();
	if($answer == true) {
		// success payment
	} else {
		// bad payment
	}
}