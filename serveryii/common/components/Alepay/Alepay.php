<?php

namespace common\components\Alepay;

use common\lib\Log4P;

/*
 * Alepay class
 * Implement with Alepay service
 */

class Alepay
{
    protected $alepayUtils;
    protected $publicKey = "";
    protected $checksumKey = "";
    protected $apiKey = "";
    protected $callbackUrl = "http://localhost/ebay/ebay/public/service/demo/test.json";
    public $URL = array(
        // url live
        'requestPayment' => 'https://alepay.vn/checkout/v1/request-order',
        'calculateFee' => 'https://alepay.vn/checkout/v1/calculate-fee',
        'getTransactionInfo' => 'https://alepay.vn/checkout/v1/get-transaction-info',
        'requestCardLink' => 'https://alepay.vn/checkout/v1/request-profile',
        'tokenizationPayment' => 'https://alepay.vn/checkout/v1/request-tokenization-payment',
        'cancelCardLink' => 'https://alepay.vn/checkout/v1/cancel-profile',
        'installmentinfo' => 'https://alepay.vn//checkout/v1/get-installment-info'
    );

    public $URL_TEST = array(
        'requestPayment' => 'https://alepay-sandbox.nganluong.vn/checkout/v1/request-order',
        'calculateFee' => 'https://alepay-sandbox.nganluong.vn/checkout/v1/calculate-fee',
        'getTransactionInfo' => 'https://alepay-sandbox.nganluong.vn/checkout/v1/get-transaction-info',
        'requestCardLink' => 'https://alepay-sandbox.nganluong.vn/checkout/v1/request-profile',
        'tokenizationPayment' => 'https://alepay-sandbox.nganluong.vn/checkout/v1/request-tokenization-payment',
        'cancelCardLink' => 'https://alepay-sandbox.nganluong.vn/checkout/v1/cancel-profile',
        'installmentinfo' => 'https://alepay-sandbox.nganluong.vn/checkout/v1/get-installment-info',
    );

    public static $InstallmentBanks = [

        [
            'bankName' => 'Ngân hàng Sacombank',
            'bankCode' => 'SACOMBANK',
            'methods' => [
                'VISA',
                'MASTERCARD',
                'JCB',
            ],
        ],
        [
            'bankName' => 'Ngân hàng VPBank',
            'bankCode' => 'VPBANK',
            'methods' => [
                'MASTERCARD',
            ],
        ],
        [
            'bankName' => 'Ngân hàng ShinhanBank',
            'bankCode' => 'SHINHANBANK',
            'methods' => [
                'VISA',
            ],
        ],
        [
            'bankName' => 'Ngân hàng EximBank',
            'bankCode' => 'EXIMBANK',
            'methods' => [
                'VISA',
                'MASTERCARD',
            ],
        ],
        [
            'bankName' => 'Ngân hàng MaritimeBank',
            'bankCode' => 'MARITIMEBANK',
            'methods' => [
                'MASTERCARD',
            ],
        ],
        [
            'bankName' => 'Ngân hàng VIB',
            'bankCode' => 'VIB',
            'methods' => [
                'MASTERCARD',
            ],
        ],
        [
            'bankName' => 'Ngân hàng HSBC',
            'bankCode' => 'HSBC',
            'methods' => [
                'VISA',
                'MASTERCARD',
            ],
        ],

        [
            'bankName' => 'NH TMCP Kỹ Thương Việt Nam',
            'bankCode' => 'TECHCOMBANK',
            'methods' => [
                'VISA',
            ],
        ],

        [
            'bankName' => 'NH TMCP Tiên Phong',
            'bankCode' => 'TPB',
            'methods' => [
                'VISA',
                'MASTERCARD'
            ],
        ],

        [
            'bankName' => 'NH TNHH MTV ANZ Việt Nam',
            'bankCode' => 'ANZ',
            'methods' => [
                'VISA'
            ],
        ],

        [
            'bankName' => 'NH CitiBank Việt Nam',
            'bankCode' => 'CTB',
            'methods' => [
                'VISA',
                'MASTERCARD',
            ],
        ],

        [
            'bankName' => 'NH TNHH MTV Standard Chartered (Việt Nam)',
            'bankCode' => 'SC',
            'methods' => [
                'MASTERCARD',
            ],
        ],

        [
            'bankName' => 'NH TMCP Sài Gòn',
            'bankCode' => 'SCB',
            'methods' => [
                'VISA',
                'MASTERCARD',
            ],
        ],
        [
            'bankName' => 'NH TNHH MTV Shinhan Việt Nam',
            'bankCode' => 'SHINHANBANK',
            'methods' => [
                'VISA',
            ],
        ],
        [
            'bankName' => 'NH TMCP Đông Nam Á',
            'bankCode' => 'SEABANK',
            'methods' => [
                'VISA',
                'MASTERCARD',
            ],
        ],

    ];
    public static $methods = [
        ['name' => 'Thẻ VISA', 'code' => 'VISA'],
        ['name' => 'Thẻ MASTERCARD', 'code' => 'MASTERCARD'],
        ['name' => 'Thẻ JCB', 'code' => 'JCB']
    ];

    public static $installmentMin = 3500000;
    public static $installmentEnable = true;

    public function __construct($opts)
    {
        header('Access-Control-Allow-Origin: *');
        header("Access-Control-Allow-Credentials: true");
        header('Access-Control-Allow-Methods: GET, PUT, POST, DELETE, OPTIONS');
        header('Access-Control-Max-Age: 1000');
        header('Access-Control-Allow-Headers: Content-Type, Content-Range, Content-Disposition, Content-Description');

        /*
         * Require curl and json extension
         */
        if (!function_exists('curl_init')) {
            throw new \Exception('Alepay needs the CURL PHP extension.');
        }
        if (!function_exists('json_decode')) {
            throw new \Exception('Alepay needs the JSON PHP extension.');
        }
        // set KEY
        if (isset($opts) && !empty($opts["apiKey"])) {
            $this->apiKey = $opts["apiKey"];
        } else {
            throw new \Exception("API key is required !");
        }
        if (isset($opts) && !empty($opts["encryptKey"])) {
            $this->publicKey = $opts["encryptKey"];
        } else {
            throw new \Exception("Encrypt key is required !");
        }
        if (isset($opts) && !empty($opts["checksumKey"])) {
            $this->checksumKey = $opts["checksumKey"];
        } else {
            throw new \Exception("Checksum key is required !");
        }
        if (isset($opts) && !empty($opts["callbackUrl"])) {
            $this->callbackUrl = $opts["callbackUrl"];
        }
        $this->alepayUtils = new \common\components\Alepay\Utils\AlepayUtils();
    }


    /*
     * Generate data checkout demo
     */
    function createCheckoutData()
    {
        $params = array(
            'amount' => '1000',
            'buyerAddress' => '12 đường 18, quận 1',
            'buyerCity' => 'TP. Hồ Chí Minh',
            'buyerCountry' => 'Việt Nam',
            'buyerEmail' => 'testalepay@yopmail.com',
            'buyerName' => 'Nguyễn Văn Bê',
            'buyerPhone' => '0987654321',
            'cancelUrl' => 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER['SERVER_PORT'] . '/demo-beta',
            'currency' => 'VND',
            'orderCode' => 'Order-123',
            'orderDescription' => 'Mua ai phôn 8',
            'paymentHours' => '5',
            'returnUrl' => $this->callbackUrl,
            'totalItem' => '1',
            'checkoutType' => 0
        );

        return $params;
    }

    private function createRequestCardLinkData()
    {
        $params = array(
            'id' => 'acb-123',
            'firstName' => 'Nguyễn',
            'lastName' => 'Văn Bê',
            'street' => 'Nguyễn Trãi',
            'city' => 'TP. Hồ Chí Minh',
            'state' => 'Quận 1',
            'postalCode' => '100000',
            'country' => 'Việt nam',
            'email' => 'testalepay@yopmail.com',
            'phoneNumber' => '0987654321',
            'callback' => $this->callbackUrl
        );
        return $params;
    }

    private function createTokenizationPaymentData($tokenization)
    {
        $params = array(
            'customerToken' => $tokenization,    // put customer's token
            'orderCode' => 'order-123',
            'amount' => '1000',
            'currency' => 'VND',
            'orderDescription' => 'Mua ai phôn 8',
            'returnUrl' => $this->callbackUrl,
            'cancelUrl' => $this->callbackUrl,
            'paymentHours' => 5
        );
        return $params;
    }


    /*
     * sendOrder - Send order information to Alepay service
     * @param array|null $data
     */
    public function sendOrderToAlepay($data)
    {
        $url = $this->GetUrlAlepay()['requestPayment'];
        $result = $this->sendRequestToAlepay($data, $url);
        if ($result->errorCode == '000') {
            $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);
        }

        return $dataDecrypted;
    }


    /**
     * Tính phí dịch vụ trả góp
     * @param $data
     * @return null|string
     */
    public function calculateInstallmentFee($data)
    {

        $result = $this->sendRequestToAlepay($data, $this->GetUrlAlepay()['calculateFee']);
        if ($result->errorCode == '000') {
            $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);
            return json_decode($dataDecrypted);
        }

        return null;
    }

    /*
     * get transaction info from Alepay
     * @param array|null $data
     */
    public function getTransactionInfo($transactionCode)
    {
        // demo data
        $data = array('transactionCode' => $transactionCode);
        $url = $this->GetUrlAlepay()['getTransactionInfo'];
        $result = $this->sendRequestToAlepay($data, $url);
        if ($result->errorCode == '000') {
            $now = time();
            //Log4P::pushLogs($result, 'PAYMENT-MAKE/Alepay/getTransactionInfo/' . date('Y-m-d') . '/' . $now . '/$result');
            $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);
            //Log4P::pushLogs($dataDecrypted, 'PAYMENT-MAKE/Alepay/getTransactionInfo/' . date('Y-m-d') . '/' . $now . '/$dataDecrypted');
            return $dataDecrypted;
        }
        return '';
    }

    /*
     * sendCardLinkRequest - Send user's profile info to Alepay service
     * return: cardlink url
     * @param array|null $data
     */
    public function sendCardLinkRequest($data)
    {
        // get demo data
        $data = $this->createRequestCardLinkData();
        $url = $this->GetUrlAlepay()['requestCardLink'];
        $result = $this->sendRequestToAlepay($data, $url);

        if ($result->errorCode == '000') {
            $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);
            $cardlinkUrl = json_decode($dataDecrypted)->url;
        }


        return $cardlinkUrl;
    }

    public function sendTokenizationPayment($tokenization)
    {

        $data = $this->createTokenizationPaymentData($tokenization);
        $url = $this->GetUrlAlepay()['tokenizationPayment'];
        $result = $this->sendRequestToAlepay($data, $url);
        echo ' ErrorCode : ' . $result->errorCode . ' - ' . $result->errorDescription . '\r\n';
        if ($result->errorCode == '000') {
            $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);
        }
        return $dataDecrypted;
    }

    public function cancelCardLink($alepayToken)
    {
        $params = array('alepayToken' => $alepayToken);
        $url = $this->GetUrlAlepay()['cancelCardLink'];
        $result = $this->sendRequestToAlepay($params, $url);
        echo ' ErrorCode : ' . $result->errorCode . ' - ' . $result->errorDescription . '\r\n';
        if ($result->errorCode == '000') {
            $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);
        }
        echo $dataDecrypted;
    }

    private function sendRequestToAlepay($data, $url)
    {
//        echo " sendRequestToAlepay --> data : ";
//        print_r($data);

        $dataEncrypt = $this->alepayUtils->encryptData(json_encode($data), $this->publicKey);

//      echo " sendRequestToAlepay --> dataEncrypt  : ";
//      print_r($dataEncrypt);

        $checksum = md5($dataEncrypt . $this->checksumKey);
        $items = array(
            'token' => $this->apiKey,
            'data' => $dataEncrypt,
            'checksum' => $checksum
        );
        $data_string = json_encode($items);
        $ch = curl_init($url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
                'Content-Type: application/json',
                'Content-Length: ' . strlen($data_string))
        );
        $result = curl_exec($ch);
        return json_decode($result);
    }

    public function return_json($error, $message = "", $data = array())
    {
        header('Content-Type: application/json');
        echo json_encode(array(
            "error" => $error,
            "message" => $message,
            "data" => $data
        ));
    }

    public function decryptCallbackData($data)
    {
        return $this->alepayUtils->decryptCallbackData($data, $this->publicKey);
    }

    public function GetUrlAlepay()
    {
        $now = time();

        if (\Yii::$app->params['environments_product_alepay']) {
            //Log4P::pushLogs($this->URL,'PAYMENT-MAKE/Alepay/GetUrlAlepay/' . date('Y-m-d') . '/' . $now . '/URL');
            return $this->URL;
        } else {
            //Log4P::pushLogs($this->URL_TEST, 'PAYMENT-MAKE/Alepay/GetUrlAlepay/' . date('Y-m-d') . '/' . $now . '/URL_TEST');
            return $this->URL_TEST;
        }
    }

    /**
     * Tính phí dịch vụ trả góp
     * @param $data
     * @return null|string
     */
    public function calculateInstallmentInfo($data)
    {

        $result = $this->sendRequestToAlepay($data, $this->GetUrlAlepay()['installmentinfo']);

        $dataDecrypted = $this->alepayUtils->decryptData($result->data, $this->publicKey);
        $now = time();
        //Log4P::pushLogs($dataDecrypted, 'PAYMENT-MAKE/Alepay/calculateInstallmentInfo/' . date('Y-m-d') . '/' . $now . '/installmentinfo');
        return json_decode($dataDecrypted);
    }

}

?>