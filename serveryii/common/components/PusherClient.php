<?php

namespace common\components;

use common\models\output\RealTime;
use Pusher;
use Yii;

class PusherClient
{

  /**
   * Đẩy data lên server
   * @param RealTime $realTime
   */
  public static function push(RealTime $realTime)
  {
    $config = Yii::$app->params['pusher'];
    $pusher = new Pusher($config['key'], $config['secret'], $config['id']);
    $pusher->trigger($realTime->channel, $realTime->event, json_encode($realTime->data));
  }

}
