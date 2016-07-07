<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
$APPLICATION->SetTitle('Массовые действия над полями элементов'); 
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/iblocktools/include.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
?>
<p>
Отфильтруйте в админке элементы инфоблока, выберите нужные (или все) и в выпадающем меню под ними выберите 'Массовая установка свойств'    
</p>
<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
