<?php
error_reporting(E_ALL ^ E_NOTICE);
@ini_set('memory_limit', '1024M');
@ini_set('display_startup_errors', 1);
@ini_set('display_errors', 1);
set_time_limit(0);
@set_magic_quotes_runtime(0);
date_default_timezone_set('Asia/Shanghai');
$root = dirname(dirname(__FILE__));
set_include_path($root . DIRECTORY_SEPARATOR . 'lib' . PATH_SEPARATOR . $root
                       . DIRECTORY_SEPARATOR . 'app' . PATH_SEPARATOR . get_include_path());
require_once  $root."/lib/Zend/Loader.php";
Zend_Loader::registerAutoload();
/* 取得配置信息 */
$config = new Zend_Config_Xml($root . "/config/config.xml", 'shop');
Zend_Registry::set('config', $config);
$xml = new Custom_Config_Xml();
$dbConfig = $xml -> getConfig();
/* 连接数据库 */
Zend_Registry::set('db', new Custom_Model_Db($dbConfig->database, false));
Zend_Registry::set('systemRoot', $root);
Zend_Registry::set('shopConfig',  $xml -> getShopConfig());
$systemRoot = Zend_Registry::get('systemRoot');
define('SYSROOT', $systemRoot);
define('SHOP_TPL_ROOT', $systemRoot.'/app/Shop/Views/scripts/');
