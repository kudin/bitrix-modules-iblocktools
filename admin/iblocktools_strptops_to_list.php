<?php 
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
$APPLICATION->SetTitle('Строковые свойства в список');
$module = 'iblocktools';
IncludeModuleLangFile(__FILE__);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $module . "/include.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
?>            
 
<form method="POST">
   <? IblocktoolsFormsHelper::showIblockListSelect(); ?>
    <br>    <br>
    <input type="text" placeholder="PROPERTY_CODE">
    <br>    <br>
    <input type="submit" name="submit" value="Преобразовать строковое свойство в список"> 
</form>

<p>*Эта форма не прощает ошибок)</p>
 
<?
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
