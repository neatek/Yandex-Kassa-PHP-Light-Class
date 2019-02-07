# Yandex-Kassa-PHP-Light-Class

### Яндекс Касса обновила API / Yandex Kassa API has been updated

> Attention! This version is too old, do not use it. Read: https://kassa.yandex.ru/docs/checkout-api/

> Внимание! Данная версия слишком старая, не используйте. Читайте: https://kassa.yandex.ru/docs/checkout-api/



Сначала кидаем пользователей на страницу с переадресацией на Яндекс.Кассу

Здесь в Settings можно указать различные параметры (подробнее смотреть в классе)

```
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
```

Далее нужно создать CheckOrder и AvisoPayment

```
<?php
require_once dirname(__FILE__).'/YandexKassa.class.php';
$ya = new YandexMoney();
if($_GET['act'] == 'checkOrder') {
	$ya->checkOrder();
}
else {
	$answer = $ya->paymentAviso();
	if($answer == true) {
		// success payment
		// code...
	} else {
		// bad payment
		// code...
	}
}
```

# Support developer
If you like my job (plugin) you can send me some $$$ on beer.

[![paypal](https://www.paypalobjects.com/en_US/i/btn/btn_donateCC_LG.gif)](https://www.paypal.me/neatek/3)

* Для русских пользователей вы можете использовать ссылку https://neatek.ru/support/ (Yandex деньги)
