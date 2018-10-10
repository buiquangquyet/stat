<?php
/**
 * Created by PhpStorm.
 * User: huynk
 * Date: 11/3/2015
 * Time: 6:12 PM
 */

namespace common\components;

class Time
{

  public static function getCurrentMonthTime()
  {
    $monthFrom = date('m', time());
    $yearFrom = date('Y', time());
    $timeFrom = mktime(0, 0, 0, $monthFrom, 1, $yearFrom);
    $timeTo = mktime(23, 59, 59, $monthFrom, cal_days_in_month(CAL_GREGORIAN, $monthFrom, $yearFrom), $yearFrom);
    return array(
      'timeFrom' => $timeFrom,
      'timeTo' => $timeTo
    );
  }

}