<?php
define("MODULE_PATH", dirname(__FILE__));

function __module_autoload($strClassName) {
    $arrPath = explode('_', $strClassName);
    for ($i = 0; $i < count($arrPath) - 1; ++$i) {
        $arrPath[$i] = strtolower($arrPath[$i]);
    }
    require_once MODULE_PATH . '/' . implode('/', $arrPath) . '.php';
}
spl_autoload_register('__module_autoload');

$strActionPath = "";

$strPathInfo = $_SERVER['PATH_INFO'];
$arrTmpPathInfo = explode('?', $strPathInfo, 1);
$arrTmpPathInfo = array_filter(explode('/', $arrTmpPathInfo[0]));
array_shift($arrTmpPathInfo);

$arrPathInfo = array();
if (!empty($arrTmpPathInfo)) {
    foreach ($arrTmpPathInfo as $strPathComp) {
        $arrPathInfo[] = $strPathComp;
    }
} else {
    $arrPathInfo = array("index");
}

array_unshift($arrPathInfo, 'actions');
$strActionClassName = end($arrPathInfo) . 'Action';
$strActionPath = MODULE_PATH . DIRECTORY_SEPARATOR . implode(DIRECTORY_SEPARATOR, $arrPathInfo) . 'Action.php';

//判断用户设备
$_SERVER['DEVICE'] = 'PC';
if (preg_match('#(mobile|nokia|iphone|ipad|android|samsung|htc|blackberry)#i', $_SERVER['USER_AGENT'])) {
    $_SERVER['DEVICE'] = 'WAP';
}

if (!file_exists($strActionPath)) {
    echo "Oooops...Nothing here";
} else {
    require_once($strActionPath);
    $objAction = new $strActionClassName;
    $objAction->process();
}
