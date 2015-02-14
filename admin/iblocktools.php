<?php 
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
$APPLICATION->SetTitle('Настройки инфоблоков');
$module = 'iblocktools';
IncludeModuleLangFile(__FILE__);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $module . "/include.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");       
?>
Актуальную версию модуля можно скачать с <a target="blank" href="http://github.com/kudin/bitrix-modules-iblocktools">http://github.com/kudin/bitrix-modules-iblocktools</a>

<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
