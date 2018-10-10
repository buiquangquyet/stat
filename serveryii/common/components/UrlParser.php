<?php
/**
 * Created by PhpStorm.
 * User: quannd
 * Date: 4/12/17
 * Time: 10:21 PM
 */

namespace common\components;


use common\models\model\Store;
use yii\base\InvalidConfigException;
use yii\web\Request;
use yii\web\UrlRule;
use yii\web\UrlRuleInterface;

class UrlParser extends UrlRule
{

  const ID_AMAZON = 'amazon';
  const ID_EBAY = 'ebay';

  const TYPE_PRODUCT = 'product';
  const TYPE_BRAND = 'brand';
  const TYPE_SEARCH = 'search';
  const TYPE_CATEGORY = 'category';

  private $_template;
  /**
   * @var string the regex for matching the route part. This is used in generating URL.
   */
  private $_routeRule;
  /**
   * @var array list of regex for matching parameters. This is used in generating URL.
   */

  public static $ruleConfig = ['class' => '\common\components\UrlParser'];


  static $storeRules = [
    'amazon.com' => [
      '<product_name:[^/]+>/dp/<product_id:[A-Za-z0-9]+>/<text2:[^/]+>' => UrlParser::ID_AMAZON . '/' . UrlParser::TYPE_PRODUCT,
      '<product_name:[^/]+>/dp/<product_id:[A-Za-z0-9]+>' => UrlParser::ID_AMAZON . '/' . UrlParser::TYPE_PRODUCT,
      'dp/<product_id:[A-Za-z0-9]+>/ref=<text2:\w+>' => UrlParser::ID_AMAZON . '/' . UrlParser::TYPE_PRODUCT,
      'dp/<product_id:[A-Za-z0-9]+>/<text2:\w+>' => UrlParser::ID_AMAZON . '/' . UrlParser::TYPE_PRODUCT,
      'dp/<product_id:[A-Za-z0-9]+>/' => UrlParser::ID_AMAZON . '/' . UrlParser::TYPE_PRODUCT,
      'dp/<product_id:[A-Za-z0-9]+>' => UrlParser::ID_AMAZON . '/' . UrlParser::TYPE_PRODUCT,
    ],
    'ebay.com' => [
      'itm/<product_name:[^/]+>/<product_id:[A-Za-z0-9]+>' => UrlParser::ID_EBAY . '/' . UrlParser::TYPE_PRODUCT,
      'itm/<product_id:[A-Za-z0-9]+>' => UrlParser::ID_EBAY . '/' . UrlParser::TYPE_PRODUCT,
    ],
    'weshop.asia' => [
      'amazon/item/<product_name:[^/]+>-<product_id:[A-Za-z0-9]+>.html' => UrlParser::ID_AMAZON . '/' . UrlParser::TYPE_PRODUCT,
      'ebay/item/<product_name:[^/]+>-<product_id:[A-Za-z0-9]+>.html' => UrlParser::ID_EBAY . '/' . UrlParser::TYPE_PRODUCT,
    ],
    'ebay.vn' => [
      'san-pham/<product_name:[^/]+>-<product_id:[A-Za-z0-9]+>.html' => UrlParser::ID_EBAY . '/' . UrlParser::TYPE_PRODUCT,
    ],
  ];

  /**
   * Lấy site url dựa vào url gốc của sản phẩm
   * @param $url
   * @return bool|string
   */
  static function detectSiteUrl($url)
  {
    return static::buildByStoreParams(static::parseLink($url));
  }

  static function buildByStoreParams($params)
  {
    $storeData = $params[0];
    $storeId = $storeData[0];
    $linkType = $storeData[1];
    $paramsData = $params[1];
    $productId = $paramsData['product_id'];
    $productName = isset($paramsData['product_name']) ? $paramsData['product_name'] : 'product';
    if ($linkType == UrlParser::TYPE_PRODUCT) {
      switch ($storeId) {
        case UrlParser::ID_AMAZON:
          return UrlComponent::detailAmazon($productId, $productName);
        case UrlParser::ID_EBAY:
          return UrlComponent::detailEbay($productId, $productName);
        default:
          return false;
      }
    }
    return false;
  }

  static $rules = [];

  static function getRuleForDomain($domain)
  {
    if (!isset(static::$rules[$domain])) {
      static::$rules[$domain] = static::buildRules(static::$storeRules[$domain]);
    }
    return static::$rules[$domain];
  }

  static $exceptionDomain = [
    'weshop.com.vn' => 'weshop.asia',
    'weshop.co.id' => 'weshop.asia',
    'weshop.co.th' => 'weshop.asia',
    'weshop.my' => 'weshop.asia',
    'weshop.ph' => 'weshop.asia',
    'weshop.sg' => 'weshop.asia',
  ];

  static function parseLink($url)
  {
    if (in_array($url[-1], ['/', '?'])) {
      $url = substr($url, 0, -1);
    }

    $urlParse = parse_url($url);

    $hostName = $urlParse['host'];

    if (isset(static::$exceptionDomain[$urlParse['host']])) {
      $domain = static::$exceptionDomain[$urlParse['host']];
    } else {
      $hostNameEx = explode('.', $hostName);
      $domain = $hostNameEx[count($hostNameEx) - 2] . '.' . $hostNameEx[count($hostNameEx) - 1];
    }

    $path = substr($urlParse['path'], 1);
    $orgParams = [];
    $orgParamLetters = [];
    parse_str(isset($urlParse['query']) ? $urlParse['query'] : '', $orgParams);
    parse_str(isset($urlParse['query']) ? strtolower($urlParse['query']) : '', $orgParamLetters);
    $matchRule = null;

    $orgParams = array_merge($orgParams, $orgParamLetters);
    foreach (static::getRuleForDomain($domain) as $rule) {
      $match = $rule->testPath($path);
      if ($match != false) {
        $match[1] = array_merge($match[1], $orgParams);
        $matchRule = $match;
        break;
      }
    }
    return $matchRule;
  }

  /**
   * Parses the given request and returns the corresponding route and parameters.
   * @param Request $request the request component
   * @return array|bool the parsing result. The route and the parameters are returned as an array.
   * If `false`, it means this rule cannot be used to parse this path info.
   */
  public function testPath($path)
  {
    if ($this->host !== null) {
      $path = ($path === '' ? '' : '/' . $path);
    }

    if (!preg_match($this->pattern, $path, $matches)) {
      return false;
    }
    $matches = $this->substitutePlaceholderNames($matches);

    foreach ($this->defaults as $name => $value) {
      if (!isset($matches[$name]) || $matches[$name] === '') {
        $matches[$name] = $value;
      }
    }
    $params = $this->defaults;
    $tr = [];

    foreach ($matches as $k => $match) {
      if (!is_numeric($k)) {
        $params[$k] = $match;
      }
    }

    if ($this->_routeRule !== null) {
      $route = strtr($this->route, $tr);
    } else {
      $route = $this->route;
    }
    return [explode('/', $route), $params];
  }

  /**
   * Builds URL rule objects from the given rule declarations.
   * @param array $rules the rule declarations. Each array element represents a single rule declaration.
   * Please refer to [[rules]] for the acceptable rule formats.
   * @return UrlRuleInterface[] the rule objects built from the given rule declarations
   * @throws InvalidConfigException if a rule declaration is invalid
   */
  static function buildRules($rules)
  {
    $compiledRules = [];
    $verbs = 'GET|HEAD|POST|PUT|PATCH|DELETE|OPTIONS';
    foreach ($rules as $key => $rule) {
      if (is_string($rule)) {
        $rule = ['route' => $rule];
        if (preg_match("/^((?:($verbs),)*($verbs))\\s+(.*)$/", $key, $matches)) {
          $rule['verb'] = explode(',', $matches[1]);
          // rules that do not apply for GET requests should not be use to create urls
          if (!in_array('GET', $rule['verb'])) {
            $rule['mode'] = UrlRule::PARSING_ONLY;
          }
          $key = $matches[4];
        }
        $rule['pattern'] = $key;
      }
      if (is_array($rule)) {
        $rule = \Yii::createObject(array_merge(static::$ruleConfig, $rule));
      }
      if (!$rule instanceof UrlRuleInterface) {
        throw new InvalidConfigException('URL rule class must implement UrlRuleInterface.');
      }
      $compiledRules[] = $rule;
    }
    return $compiledRules;
  }
}