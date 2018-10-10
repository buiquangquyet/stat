<?php
/**
 * Created by PhpStorm.
 * User: quannd
 * Date: 3/17/17
 * Time: 6:58 PM
 */

namespace common\components\Alepay;


use common\models\service\OrderService;

class OrderAlepayAddfee extends \common\models\model\OrderAlepayAddfee
{
    public static function updateInfo($data)
    {
        $order = OrderService::getOrderByBin($data->orderCode);
        $logForOrder = static::findOne(['order_bin_code' => $order->binCode]);
        if (empty($logForOrder)) {
            $logForOrder = new OrderAlepayAddfee();
            $logForOrder->order_bin_code = $order->binCode;
            $logForOrder->save(false);
        }
        $logForOrder->transaction_code = $data->transactionCode;
        $logForOrder->amount = $data->amount;
        $logForOrder->currency = $data->currency;
        $logForOrder->status = $data->status;
        $logForOrder->message = $data->message;
        $logForOrder->is_3d = $data->is3D;
        $logForOrder->month = $data->month;
        $logForOrder->bank_code = $data->bankCode;
        $logForOrder->bank_name = $data->bankName;
        $logForOrder->method = $data->method;
        $logForOrder->success_time = $data->successTime;
        return $logForOrder->save(false);
    }

    public static function updateByOrder($orderBinCode, $installment = false, $orderTotal = 0, $installmentFee = 0)
    {
        $logForOrder = static::findOne(['order_bin_code' => $orderBinCode]);
        if (empty($logForOrder)) {
            $logForOrder = new OrderAlepayAddfee();
            $logForOrder->order_bin_code = $orderBinCode;
            $logForOrder->save(false);
        }
        $logForOrder->installment = $installment;
        $logForOrder->order_amount = $orderTotal;
        $logForOrder->fee_amount = $installmentFee;
        $logForOrder->save(false);
        return $logForOrder;
    }
}