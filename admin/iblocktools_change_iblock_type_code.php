<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
$APPLICATION->SetTitle('Изменение кода типу инфоблока');
$module = 'iblocktools';
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $module . "/include.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
CModule::IncludeModule('iblock');

if ($_REQUEST['submit']) { 
    $DB->Query("UPDATE b_iblock set IBLOCK_TYPE_ID = '{$_REQUEST['NEW']}' 
                where IBLOCK_TYPE_ID = '{$_REQUEST['IBLOCK_TYPE']}' ;"); 
    
    $DB->Query("UPDATE b_iblock_type set ID = '{$_REQUEST['NEW']}'  
                where ID = '{$_REQUEST['IBLOCK_TYPE']}';"); 
    
    $DB->Query("UPDATE b_iblock_type_lang set IBLOCK_TYPE_ID = '{$_REQUEST['NEW']}' 
                where IBLOCK_TYPE_ID = '{$_REQUEST['IBLOCK_TYPE']}';");
    ?>
    <p>Код инфоблока изменён</p>
    <p>Очистите содержимое <a href="/bitrix/admin/fileman_admin.php?lang=ru&path=%2Fbitrix%2Fmanaged_cache&site=">/bitrix/managed_cache/</a> если в админке инфоблоки показываются неверно</p>
    <?
} else {
    ?>    
    <form method="POST" method="POST"> <? $res = CIBlock::GetList(array("ID" => "ASC"), array(), false); ?> 
        Переименовать <? IblocktoolsFormsHelper::showIblockTypesSelect(); ?>
        в <input type="text" name="NEW">
        <br><br>
        <input type="submit" name="submit" value="Изменить тип инфоблока"> 
    </form> 
    <?
}
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
