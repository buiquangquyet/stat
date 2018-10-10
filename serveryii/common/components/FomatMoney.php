<?php

namespace common\components;

class FomatMoney
{

  public static function money_format($format, $number, $currencyName = false)
  {
    return number_format($number, 2);
  }

  public static function number_format($number, $str = false)
  {
    if (!empty($number)) {
      if ($str == '.') {
        $number = str_replace(',', '', $number);
      } else if ($str == ',') {
        $arrnumber = @explode(',', $number);
        if (!empty($arrnumber[1])) {
          $number = str_replace('.', '', $arrnumber[0]);
          $number = (int)$number;
        } else {
          $number = (int)$number;
        }
      }
    } else {
      $number = 0;
    }
    if (strpos($number, '.') !== false) {

    } else {
      $number = $number . '.00';
    }
    return trim($number);
  }

  public static function numberFormatByStoreId($number, $storeId = 1)
  {
    switch ($storeId) {
      case 1:
        return ceil($number);
      case 6:
        return $number;
      case 7:
        return ceil($number);
      case 8:
        return $number;
      case 9:
        return ceil($number);
        break;
      case 10:
        return ceil($number);
        break;
      default:
        return ceil($number);
        break;
    }
  }

  public static function number_format_vn($number)
  {
    if (!empty($number)) {
      $array = @explode('.', $number);
      $number = @$array[0];
    } else {
      $number = 0;
    }
    return trim($number);
  }

}
