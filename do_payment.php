<?php
require_once dirname(__FILE__).'/YandexKassa.class.php';
$sid = (int) $_GET['sid'];
$settings = array(
    'sum' => 1.0,
    'email' => 'neatek@icloud.com',
    'customerNumber' => $sid
);
$ya = new YandexMoney($settings);
$ya->goForm();