<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
$APPLICATION->SetTitle('Простановка символьных кодов разделам');
$module = 'iblocktools';
IncludeModuleLangFile(__FILE__);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $module . "/include.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
CModule::IncludeModule('iblock');

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
    $sections = CIBlockSection::GetList($arOrder, $arFilter, false);
    echo '<pre>';
    while ($ar_res = $sections->GetNext()) {
        $code = CUtil::translit($ar_res["NAME"], "ru", $arTransParams);
        $arPropArray = Array("CODE" => $code);
        $el = new CIBlockSection;
        $res = $el->Update($ar_res["ID"], $arPropArray);
        if ($res) {
            echo "Разделу [" . $ar_res["ID"] . "] <b>" . $ar_res["NAME"] . "</b> установлен код <b>" . $code . '</b>';
        } else {
            echo "Ошибка. Разделу [" . $ar_res["ID"] . "] <b>" . $ar_res["NAME"] . "</b> не установлен код. <b>" . $el->LAST_ERROR . "</b>";
        }
        echo "\r\n";
    }
    echo '</pre>';
} else {
    ?>    
    <form method="POST" method="POST"> <? $res = CIBlock::GetList(array("ID" => "ASC"), array(), false); ?> 
        <select name="IBLOCK_ID"> 
            <? while ($ar_res = $res->Fetch()) { ?>
                <option value="<?= $ar_res['ID'] ?>">[<?= $ar_res['ID'] ?>] <?= $ar_res['NAME'] ?></option> 
            <? } ?>
        </select>
        <br><br>
        <input type="submit" name="submit" value="Установить символьные коды разделам"> 
    </form> 
    <?
}
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
