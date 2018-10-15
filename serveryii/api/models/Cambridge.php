<?php
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 15/10/2018
 * Time: 09:23
 */

namespace api\models;


use common\components\Cambridge\SkPublishAPIRequestHandler;

class Cambridge implements SkPublishAPIRequestHandler {
    public function prepareGetRequest($curl, $uri, &$headers) {
        print($uri."\n");
        $headers[] = "Accept: application/json";
    }
}