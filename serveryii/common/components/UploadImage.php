<?php

namespace common\components;

class UploadImage
{

  public static function checkImage($image = array())
  {
    $arr = array('image/jpeg', 'image/png', 'image/gif');
    if (!in_array($image['type'], $arr)) {
      throw new \RuntimeException('Invalid file format.');
    }

    switch ($image['error']) {
      case UPLOAD_ERR_OK:
        break;
      case UPLOAD_ERR_NO_FILE:
        throw new \RuntimeException('No file sent.');
      case UPLOAD_ERR_INI_SIZE:
      case UPLOAD_ERR_FORM_SIZE:
        throw new \RuntimeException('Exceeded filesize limit.');
      default:
        throw new \RuntimeException('Unknown errors.');
    }
    if ($image['size'] > 1000000) {
      throw new \RuntimeException('Exceeded filesize limit.');
    }

    return true;
  }

  public static function upload($file, $storeData, $type = null)
  {
    $staticDomain = isset($storeData->baseUploadUrl) ? $storeData->baseUploadUrl : 'http://static.weshop.com.vn';
    if ($type === null) {
      if (!TextUtility::isImage($file['name'])) {
        return false;
      }
    }

    $fileName = $file['name'];
    $handle = fopen($file['tmp_name'], "r");
    $data = fread($handle, filesize($file['tmp_name']));

    $params = array(
      'file' => base64_encode($data),
      'name' => $fileName
    );

    $functionName = 'add';


    return self::call($staticDomain, $functionName, $params);
  }

  public static function UnzipFile($file, $type = null, $storeData)
  {
    $staticDomain = isset($storeData->baseUploadUrl) ? $storeData->baseUploadUrl : 'http://static.weshop.com.vn';
    $params = array(
      'file' => base64_encode($file),
    );
    $functionName = 'unzipfile';

    return self::call($staticDomain, $functionName, $params);
  }

  public static function remove($fileUrl)
  {
    $staticDomain = \Yii::$app->params['staticDomain'];
    $params = array(
      'url' => $fileUrl
    );
    $functionName = 'del';

    return self::call($staticDomain, $functionName, $params);
  }

  private static function call($url, $functionName, $params)
  {
    $urlPost = $url . '/upload/' . $functionName . '.json';
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $urlPost);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    $server_output = curl_exec($ch);
    curl_close($ch);
    return self::convertToObject($server_output);
  }

  private function convertToObject($output)
  {
    $obj = json_decode($output);
    return $obj;
  }

}

?>