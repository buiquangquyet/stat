<?php
/**
 * Created by PhpStorm.
 * User: huylx
 * Date: 04/11/2016
 * Time: 10:00 AM
 */

namespace common\components;

use common\models\model\SystemWeightUnit;


class ExtraClient
{
    public static function weightConvert($fromWeightUnitId, $toWeightUnitId, $fromWeight){
        $fromWeightUnitData = SystemWeightUnit::findOne(['id' => $fromWeightUnitId]);
        $toWeightUnitData = SystemWeightUnit::findOne(['id' => $toWeightUnitId]);
        if(empty($fromWeightUnitData) || empty($toWeightUnitData)){
            return $fromWeight;
        }
        if($fromWeightUnitData->Ratio <= 0){
            return $fromWeight;
        }
        $toWeight = ($fromWeight * $toWeightUnitData->Ratio) / $fromWeightUnitData->Ratio;
        return $toWeight;
    }
}