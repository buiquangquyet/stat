<?php

namespace common\components;

class FeeBoxme
{
    static $baseClientUrl = 'http://seller.boxme.id/courier/caculate_courier_order_new';

    static function callApi($data = null)
    {
        $ch = curl_init(self::$baseClientUrl);
        $payload = json_encode($data);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type:application/json'));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        $response = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($response, true);
        return $res;
    }

    static function calcFee($district_id, $city_id, $amount = 100000, $weight = 1)
    {
        $weight *= 1000;
        $protected = 2;
        if($amount > 10000000){
            $protected = 1;
        }
        $data = [
            'From' => [
                'City' => 501,
                'Province' => 7155,
                'Stock' => 0
            ],
            'To' => [
                "City" => $city_id,
                "Province" => $district_id,
                "Country" => 101, // Mặc đinh chỉ indo  - mã quốc gia là 101
                "Address" => "Address Default"
            ],
            "Order" => [
                "Amount" => floatval($amount),
                "Quantity" => 1,
                "Weight" => $weight
            ],
            "Config" => [
                "Service" => 11,
                "CoD" => 2,
                "Protected" => $protected,
                "Checking" => 2,
                "Payment" => 1,
                "Fragile" => 2
            ],
            "Domain" => "seller.boxme.id",
            "MerchantKey" => "e80d1bb5cde172364fdd6c338b8966ac"
        ];
        $res = self::callApi($data);
        $result = 0;

        if ($res['code'] == "SUCCESS" && $res['error_message'] == 'success' && !$res['error']) {
            $courier = $res['data']['courier']['system'];
            if (count($courier) > 1) {
                $data_courier = $courier[0];
                foreach ($courier as $item) {
                    if ($item['courier_id'] == 28) {
                        $data_courier = $item;
                        break;
                    }
                    if(array_key_exists('total', $item['fee']) && array_key_exists('total', $item['fee'])){
                        if ($item['fee']['total'] < $data_courier['fee']['total']) {
                            $data_courier = $item;
                        }
                    }
                }
                $result = $data_courier['fee']['total'];

            } else {
                $result = $courier[0]['fee']['total'];
            }
        }

        return $result;
    }
}

?>