<?php

class iblocktools extends CModule {
 
    const MODULE_ID = "iblocktools";
    var $MODULE_ID = "iblocktools";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME; 
    var $MODULE_DESCRIPTION; 
    var $MODULE_GROUP_RIGHTS; 
    var $errors = array();
 
    function __construct() {
        $arModuleVersion = array();

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path . "/version.php");

        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }

        $this->MODULE_NAME = 'Настройки для инфоблоков';
        $this->MODULE_DESCRIPTION = 'Актуальную версию модуля можно скачать с <a target="blank" href="http://github.com/kudin/bitrix-modules-iblocktools">http://github.com/kudin/bitrix-modules-iblocktools</a>'; 
    }

    function DoInstall() {
        CopyDirFiles($_SERVER['DOCUMENT_ROOT'] . '/bitrix/modules/iblocktools/install/bitrix_files/', 
                     $_SERVER['DOCUMENT_ROOT'] . '/bitrix/', true, true);
        RegisterModule(self::MODULE_ID);
    }

    function DoUninstall() {
        DeleteDirFiles($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/iblocktools/install/bitrix_files/", 
                       $_SERVER["DOCUMENT_ROOT"] . "/bitrix/");
        UnRegisterModule(self::MODULE_ID);
    }

}
