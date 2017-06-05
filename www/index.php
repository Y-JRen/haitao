<?php
/**
 * 引导文件
 */
@header('Content-type:text/html;charset=UTF-8');
error_reporting(E_ALL ^ E_NOTICE);
@ini_set('memory_limit', '512M');
@ini_set('display_startup_errors', 1);
@ini_set('display_errors', 1);
@set_magic_quotes_runtime(0);
date_default_timezone_set('Asia/Shanghai');
set_include_path(dirname($_SERVER['DOCUMENT_ROOT']) . DIRECTORY_SEPARATOR . 'lib' . PATH_SEPARATOR . dirname($_SERVER['DOCUMENT_ROOT']) . DIRECTORY_SEPARATOR . 'app' .PATH_SEPARATOR . get_include_path());
$_SERVER['REMOTE_ADDR'] = isset($_SERVER['HTTP_X_REAL_IP']) ? $_SERVER['HTTP_X_REAL_IP'] : $_SERVER['REMOTE_ADDR'];
if(!empty($_SERVER["REDIRECT_URL"]))$_SERVER["REQUEST_URI"]=$_SERVER["REDIRECT_URL"];
$systemRoot = dirname($_SERVER['DOCUMENT_ROOT']);
define('SYSROOT', $systemRoot);
require_once $systemRoot . "/lib/Zend/Loader.php";
Zend_Loader::registerAutoload();
$app=new app($systemRoot);
$app->run();
exit;