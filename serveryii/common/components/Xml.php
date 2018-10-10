<?php
namespace common\components;
use yii\httpclient\XmlParser;
/**
 * Created by PhpStorm.
 * User: quangquyet
 * Date: 06/02/2018
 * Time: 13:54
 */
class Xml extends XmlParser
{
    public function PaserXmlToArray($array)
    {
        return $this->convertXmlToArray($array);
    }
}