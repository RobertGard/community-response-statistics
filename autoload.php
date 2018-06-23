<?php

 
//функция автозагруки, загружающая классы из папки Vendor:
function loadFromVendor($aClassName) {
    $aClassFilePath = ROOT_PATH . DIRECTORY_SEPARATOR . 'vendor' . DIRECTORY_SEPARATOR . $aClassName . '.php';
    if (file_exists($aClassFilePath)) {
        require_once $aClassFilePath;
        return true;
    }
    return false;
}

//функция автозагруки, загружающая классы из папки Controllers:
function loadFromControllers($aClassName) {
    $aClassFilePath = ROOT_PATH . DIRECTORY_SEPARATOR . 'controllers' . DIRECTORY_SEPARATOR . $aClassName . '.php';
    if (file_exists($aClassFilePath)) 
    {
        require_once $aClassFilePath;
        return true;
    }
    return false;
}

//регистрируем обе функции автозагрузки
spl_autoload_register('loadFromVendor');
spl_autoload_register('loadFromControllers');