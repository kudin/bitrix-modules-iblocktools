<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
$APPLICATION->SetTitle('Изменение типа инфоблока');
$module = 'iblocktools';
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $module . "/include.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
CModule::IncludeModule('iblock');

if ($_REQUEST['submit']) { 
    $DB->Query("UPDATE b_iblock set IBLOCK_TYPE_ID = '{$_REQUEST["IBLOCK_TYPE"]}' where ID = {$_REQUEST["IBLOCK_ID"]};");
    ?>
    <p>Тип инфоблока изменён</p>
    <p><a href="/bitrix/admin/iblock_list_admin.php?IBLOCK_ID=<?=$_REQUEST["IBLOCK_ID"];?>&type=<?=$_REQUEST["IBLOCK_TYPE"];?>&lang=ru&find_section_section=0">Перейти в инфоблок</a></p>
    <?
} else {
    ?>    
    <form method="POST" method="POST"> <? $res = CIBlock::GetList(array("ID" => "ASC"), array(), false); ?> 
        <? IblocktoolsFormsHelper::showIblockListSelect(); ?>
        <br><br>
        <? IblocktoolsFormsHelper::showIblockTypesSelect(); ?>
        <br><br>
        <input type="submit" name="submit" value="Изменить тип инфоблока"> 
    </form> 
    <?
}
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
