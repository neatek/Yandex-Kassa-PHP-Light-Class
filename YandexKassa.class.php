<?php
// YandexKassa
// Vladimir Zhelnov / neatek.ru / 2017
// Light easy php class for get payments
Class YandexMoney {
    // kassa settings
    protected $ya_url = 'https://money.yandex.ru/eshop.xml';
    protected $shopId = 'HERE_SHOP_ID';
    protected $scid = 'HERE_SCID';
    protected $password = 'HERE_SHOP_PASSWORD';
    // payment settings
    protected $customerNumber = '0';
    protected $sum = '1';
    protected $email = '';
    protected $logs_folder = '';

    function __construct($settings = array()) {
        // override settings
        if(isset($settings['ya_url'])) $this->ya_url = $settings['ya_url'];
        if(isset($settings['shopId'])) $this->shopId = $settings['shopId'];
        if(isset($settings['scid'])) $this->scid = $settings['scid'];
        if(isset($settings['customerNumber'])) $this->customerNumber = $settings['customerNumber'];
        if(isset($settings['sum'])) $this->sum = $settings['sum'];
        if(isset($settings['email'])) $this->email = $settings['email'];
        if(isset($settings['password'])) $this->password = $settings['password'];
        if(isset($settings['logs_folder'])) $this->logs_folder = $settings['logs_folder'];
    }

    public function checkOrder() {
        // accept payment anyway!
        $invoiceId = 0;
        if(isset($_REQUEST['invoiceId'])) $invoiceId = (int) $_REQUEST['invoiceId'];
        $performedDatetime = date("Y-m-d") . "T" . date("H:i:s") . ".000" . date("P");
        header('Content-Type:text/xml');
        echo '<?xml version="1.0" encoding="UTF-8"?><checkOrderResponse performedDatetime="'.$performedDatetime.'" code="0" invoiceId="'.$invoiceId.'" shopId="28315"/>';
        //die();
    }

    private function checkMD5($request) {
        $str = $request['action'] . ";" .
            $request['orderSumAmount'] . ";" . $request['orderSumCurrencyPaycash'] . ";" .
            $request['orderSumBankPaycash'] . ";" . $request['shopId'] . ";" .
            $request['invoiceId'] . ";" . $request['customerNumber'] . ";" . $this->password;
        //$this->log("String to md5: " . $str);
        $md5 = strtoupper(md5($str));
        if ($md5 != strtoupper($request['md5'])) {
            //$this->log("Wait for md5:" . $md5 . ", recieved md5: " . $request['md5']);
            return false;
        }
        return true;
    }

    public function log($text) {
        if(empty($this->logs_folder))
            file_put_contents(dirname(__FILE__).'/yandex_logs.log', date('[d.m.Y - H:i:s]').' - '.$text."\r\n", FILE_APPEND);
        else
            file_put_contents($this->logs_folder.'/yandex_logs.log', date('[d.m.Y - H:i:s]').' - '.$text."\r\n", FILE_APPEND);
    }

    public function paymentAviso() {
        $invoiceId = 0;
        if(isset($_REQUEST['invoiceId'])) $invoiceId = (int) $_REQUEST['invoiceId'];
        $performedDatetime = date("Y-m-d") . "T" . date("H:i:s") . ".000" . date("P");
        header('Content-Type:text/xml');
        echo '<?xml version="1.0" encoding="UTF-8"?>';

        if($this->checkMD5($_REQUEST)) {
            $this->log ( print_r($_REQUEST,true) );
            echo '<paymentAvisoResponse performedDatetime="'.$performedDatetime.'" code="0" invoiceId="'.$invoiceId.'" shopId="28315"/>';
            return true;
        }
        
        $this->log ( print_r($_REQUEST,true) );
        echo '<paymentAvisoResponse performedDatetime="'.$performedDatetime.'" code="1" invoiceId="'.$invoiceId.'" shopId="28315"/>';
        return false;
    }

    public function goForm() {
        // prepare redirect form
        echo '<!DOCTYPE html>
        <html lang="en">
        <head>
            <meta charset="UTF-8">
            <title>Переход в Яндекс.Касса</title>
        </head>
        <body>
        <form id="yaform" action="'.$this->ya_url.'" method="post">
                <!-- Обязательные поля -->
                <input name="shopId" value="'.$this->shopId.'" type="hidden"/>
                <input name="scid" value="'.$this->scid.'" type="hidden"/>
                <input name="customerNumber" value="'.$this->customerNumber.'" type="hidden"/>';
                if(!empty($this->email)) echo '<input name="cps_email" value="'.trim($this->email).'" type="hidden"/>';
                echo '<input name="sum" value="'.$this->sum.'" type="hidden">
                <input type="submit" value="Нажмите на данную кнопку если переадресация автоматически не произошла"/>
                </form>
                <script type="text/javascript">document.getElementById(\'yaform\').submit();</script>
        </body>
        </html>';
    }

}

/***
$settings = array(
    'sum' => 1.0,
    'email' => 'neatek@icloud.com',
    'customerNumber' => $sid
);

$ya = new YandexMoney($settings);
$ya->goForm();
// $ya->checkOrder();
***/