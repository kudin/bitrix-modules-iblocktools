<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
$APPLICATION->SetTitle('Генерация символьных кодов элементов');
$module = 'iblocktools'; 
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $module . "/include.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

if ($_REQUEST['submit']) {
    $arFilter = array(
        "IBLOCK_ID" => $_REQUEST['IBLOCK_ID'],
        "CODE" => false
    );
    $arTransParams = array(
        "max_len" => 100,
        "change_case" => 'L',
        "replace_space" => '-',
        "replace_other" => '-',
        "delete_repeat_replace" => true
    );
    $db_elemens = CIBlockElement::GetList($arOrder, $arFilter, false, false); 
    echo '<pre>';
    while ($obElement = $db_elemens->GetNextElement()) {
        $ar_res = $obElement->GetFields();
        $code = CUtil::translit($ar_res["NAME"], "ru", $arTransParams);
        $arPropArray = Array("CODE" => $code);
        $el = new CIBlockElement;
        $res = $el->Update($ar_res["ID"], $arPropArray); 
        if ($res) {
            echo "Элементу [" . $ar_res["ID"] . "] <b>" . $ar_res["NAME"] . "</b> установлен код <b>" . $code . '</b>';
        } else {
            echo "Ошибка. Элементу [" . $ar_res["ID"] . "] <b>" . $ar_res["NAME"] . "</b> не установлен код. <b>" . $el->LAST_ERROR . "</b>";
        }
        echo "\r\n";
    }
    echo '</pre>';
} else {
    ?> 
    <form method="POST" method="POST">
        <? IblocktoolsFormsHelper::showIblockListSelect(); ?>
        <br><br>
        <input type="submit" name="submit" value="Установить символьные коды элементам"> 
    </form> 
    <?
}
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
