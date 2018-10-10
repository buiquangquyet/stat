<?php
/**
 * Created by PhpStorm.
 * User: nghipt
 * Date: 12/7/2015
 * Time: 5:00 PM
 */

namespace common\components;


use common\models\enu\OrderType;
use common\models\enu\ShippingOptionSettingApply;
use common\models\output\Response;
use common\models\service\CustomerService;
use common\models\service\OrderService;
use common\models\service\PosOrderService;
use common\models\service\RateService;
use common\models\service\ShipmentService;
use common\models\service\UnitService;
use common\models\service\WarehouseService;


class ChargeService
{
  public static function getChargeByCondition($params)
  {
    $storeId = !empty($params['StoreId']) ? $params['StoreId'] : 0;
    $receiverCityId = !empty($params['ReceiverCityId']) ? $params['ReceiverCityId'] : 0;
    $receiverDistrictId = !empty($params['ReceiverDistrictId']) ? $params['ReceiverDistrictId'] : 0;
    $serviceType = !empty($params['ServiceType']) ? $params['ServiceType'] : 0;
    $packageBoxHeight = !empty($params['PackageBoxHeight']) ? $params['PackageBoxHeight'] : 0;
    $packageBoxWidth = !empty($params['PackageBoxWidth']) ? $params['PackageBoxWidth'] : 0;
    $packageBoxLenght = !empty($params['PackageBoxLenght']) ? $params['PackageBoxLenght'] : 0;
    $packageDimensionUnitId = !empty($params['PackageDimensionUnitId']) ? $params['PackageDimensionUnitId'] : 0;
    $packageActualWeight = !empty($params['PackageActualWeight']) ? $params['PackageActualWeight'] : 0;
    $packageWeightUnitId = !empty($params['PackageWeightUnitId']) ? $params['PackageWeightUnitId'] : '';
    $packageTotalItem = !empty($params['PackageTotalItem']) ? $params['PackageTotalItem'] : 0;
    $packageTotalPrice = !empty($params['PackageTotalPrice']) ? $params['PackageTotalPrice'] : 0;
    $itemQuantity = !empty($params['ItemQuantity']) ? $params['ItemQuantity'] : 0;
    $itemPrice = !empty($params['ItemPrice']) ? $params['ItemPrice'] : 0;
    $categoryCustomPolicyId = !empty($params['CategoryCustomPolicyId']) ? $params['CategoryCustomPolicyId'] : 0;
    $customerId = !empty($params['CustomerId']) ? $params['CustomerId'] : 0;
    $warehouseToId = !empty($params['WarehouseToId']) ? $params['WarehouseToId'] : 0;
    $warehouseFromId = !empty($params['WarehouseFromId']) ? $params['WarehouseFromId'] : 0;
    $orderId = !empty($params['OrderId']) ? $params['OrderId'] : 0;
    $orderItemId = !empty($params['OrderItemId']) ? $params['OrderItemId'] : 0;
    $isService = !empty($params['IsService']) ? $params['IsService'] : 0;
    $internationFee = !empty($params['InternationFee']) ? $params['InternationFee'] : 0;
    $applyPackage = !empty($params['ApplyPackage']) ? $params['ApplyPackage'] : 0;
    $applyWarehouse = !empty($params['ApplyWarehouse']) ? $params['ApplyWarehouse'] : 0;
    $serviceIds = !empty($params['ServiceIds']) ? $params['ServiceIds'] : '';
    // Lay thong tin warehouse
    $warehouseToData = WarehouseService::getWarehouseById($warehouseToId);
    if (empty($warehouseToData)) {
      return new Response(false, 'Warehouse To Is Empty !', []);
    }
    $warehouseFromData = WarehouseService::getWarehouseById($warehouseFromId);
    if (empty($warehouseFromData)) {
      return new Response(false, 'Warehouse From Is Empty !', []);
    }
    $currencyToData = RateService::getSystemCurrencyById($warehouseFromData->CurrencyId);
    if (empty($currencyToData)) {
      return new Response(false, 'Currency To Is Empty !', []);
    }
    // Lay ti gia hien tai
    $exRate = RateService::getRate($warehouseFromData->CurrencyId, $warehouseToData->CurrencyId);
    // Lay thong tin ve can nang tinh phi
    $chargeWeight = self::calChargeWeight($packageActualWeight, $packageBoxHeight, $packageBoxWidth, $packageBoxLenght, $packageDimensionUnitId, $packageWeightUnitId);

    // Lay thong tin khach hang
    $customerData = CustomerService::getById($customerId);

    $isShop = false;
    if ($serviceType == OrderType::BUYNOW) {
      $isShop = true;
    }

    $totalFee = 0;
    $objectData = new \stdClass();
    if (empty($serviceIds)) {
      // Ap dung doi voi phi co ban
      $objectData->storeId = $storeId;
      switch ($isService) {
        case ShippingOptionSettingApply::FEE_INTERNATIONAL_SHIPPING:
          if (empty($packageTotalPrice) || empty($packageTotalItem) || empty($chargeWeight)) {
            return new Response(false, 'Params Request Not Validate !', []);
          }
          $objectData->TotalDelareValues = $packageTotalPrice;
          $objectData->TotalItems = $packageTotalItem;
          $objectData->ChargedWeight = $chargeWeight;
          $totalFee = ShipmentService::setInternationalFee($customerData, $objectData, $exRate, false, $isShop);
          break;
        case ShippingOptionSettingApply::FEE_SPECIAL_CUSTOM:
          if (empty($categoryCustomPolicyId) || empty($itemQuantity) || empty($itemPrice)) {
            return new Response(false, 'Params Request Not Validate !', []);
          }
          $objectData->price = $itemPrice;
          $objectData->quantity = $itemQuantity;
          $objectData->weight = 0;
          $objectData->categoryId = $categoryCustomPolicyId;

          $totalFee = ShipmentService::setCustomFees($customerData, $objectData, $exRate, $isShop);
          break;
        case ShippingOptionSettingApply::FEE_WESHOP_FEE_SERVICE:
          if (empty($internationFee) || empty($packageTotalPrice)) {
            return new Response(false, 'Params Request Not Validate !', []);
          }
          $objectData->internationalFee = $internationFee;
          $objectData->price = $packageTotalPrice;

          $totalFee = ShipmentService::setWeshopServiceFee($customerData, $objectData, $exRate, false, $isShop);
          break;
        case ShippingOptionSettingApply::FEE_DELIVERY_LOCAL:
          if (empty($packageTotalPrice) || empty($receiverCityId) || empty($receiverDistrictId) || empty($chargeWeight)) {
            return new Response(false, 'Params Request Not Validate !', []);
          }
          $objectData->TotalDelareValues = $packageTotalPrice;
          $objectData->ReceiverProvinceId = $receiverCityId;
          $objectData->ReceiverDistrictId = $receiverDistrictId;
          $objectData->ChargedWeight = $chargeWeight;

          $totalFee = ShipmentService::setLocalFee($warehouseToData, $objectData, $exRate, false);
          break;
        case ShippingOptionSettingApply::FEE_DELIVERY_LOCAL_COD:
          $orderData = OrderService::getOrderById($orderId);
          if (empty($orderData)) {
            return new Response(false, 'Order Data Is Empty !', []);
          }

          $totalChargeLocal = $orderData->OrderTotalInLocalCurrencyFinal + $orderData->AdditionFeeLocalAmount;
          if ($isShop) {
            $totalPaidLocal = $orderData->TotalPaidAmount + $orderData->AdditionFeePaidLocalAmount;
          } else {
            $totalPaidLocal = $orderData->OrderTotalInLocalCurrencyFinal + $orderData->AdditionFeePaidLocalAmount;
          }
          $totalUnPaidLocal = $totalChargeLocal - $totalPaidLocal;

          if ($totalUnPaidLocal > 0 && $totalUnPaidLocal < $totalPaidLocal) {
            $objectData->price = round($totalUnPaidLocal / $exRate, 2);
            $objectData->quantity = $packageTotalItem;
            $objectData->weight = $chargeWeight;

            $totalFee = ShipmentService::setCodFee($customerData, $objectData, $exRate, $isShop, false);
          }

          break;
      }
    } else {
      // Ap dung doi voi Option nang cao
      // App dung voi package
      $objectData->Weight = 0;
      if ($applyPackage == ShippingOptionSettingApply::APPLY_PACKAGE) {
        $objectData->UnitPrice = $packageTotalPrice;
        $objectData->Quantity = $packageTotalItem;
      } else {
        $objectData->UnitPrice = $itemPrice;
        $objectData->Quantity = $itemQuantity;
      }
      if (!empty($serviceIds)) {
        $serviceArrIds = explode(',', $serviceIds);
      }
      if (!empty($serviceArrIds)) foreach ($serviceArrIds as $serviceItemId) {
        $serviceChooseData = [];
        if ($applyWarehouse == ShippingOptionSettingApply::APPLY_WAREHOUSE) {
          $serviceChooseData = OptionSettingService::getWarehousePriceById($serviceItemId);
        } else {
          $serviceChooseData = OptionSettingService::getShippingPriceById($serviceItemId);
        }
        $serviceFee = PosOrderService::getFeeService($objectData, $serviceChooseData);

        if ($serviceFee > 0) {
          $totalFee += $serviceFee;
        }
      }
    }
    $data['totalFee'] = $totalFee;
    $data['currencyId'] = $currencyToData->id;
    $data['currencyName'] = $currencyToData->Name;

    return new Response(true, 'Total Fee', $data);
  }

  /**
   * Tinh toan trong luong thu
   * @param unknown $actualWeight
   * @param unknown $boxHeight
   * @param unknown $boxWidth
   * @param unknown $boxLenght
   * @param unknown $dimensionUnitId
   * @param unknown $weightUnitId
   */
  private static function calChargeWeight($actualWeight, $boxHeight, $boxWidth, $boxLenght, $dimensionUnitId, $weightUnitId)
  {
    $chargeWeight = 0;
    $weightBox = 0;
    $factorDimension = 1;
    $factorWeight = 1;
    $dimensionUnitData = UnitService::getUnitDemensionById($dimensionUnitId);
    $weightUnitData = UnitService::getUnitWeightById($weightUnitId);

    // Quy doi neu la Met
    if (!empty($dimensionUnitData)) {
      $factorDimension = $dimensionUnitData->Ratio;
    }

    // Quy doi neu la Kg
    if (!empty($weightUnitData)) {
      $factorWeight = $weightUnitData->Ratio;
    }

    // Khoi luong hop
    $weightBox = round(($boxHeight * $boxWidth * $boxLenght * $factorDimension) / 5, 0);

    // Khoi luong thuc
    $actualWeight = $actualWeight * $factorWeight;

    if ($weightBox > $actualWeight) {
      $chargeWeight = $weightBox;
    } else {
      $chargeWeight = $actualWeight;
    }

    if ($chargeWeight) {
      $chargeWeight = 1.1 * $chargeWeight;
      if ($chargeWeight % 500 != 0) {
        $chargeWeight = (int)($chargeWeight / 500);
        $chargeWeight = (1 + $chargeWeight) * 500;
      }
    }

    return $chargeWeight;
  }
}