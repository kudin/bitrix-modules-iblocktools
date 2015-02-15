<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
$APPLICATION->SetTitle('Строковые свойства в список');
$module = 'iblocktools';
CJSCore::Init(array("jquery"));
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $module . "/include.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
$step = intval($_REQUEST['step']);
switch ($step) {
    case 1:
        $PROPERTY_ID = $_REQUEST['PROPERTY_ID'];
        if (!$PROPERTY_ID) {
            ShowError('Не выбрано свойство');
            break;
        } 
        $_SESSION['PROP_VALUES'] = array();
        $_SESSION['PROP_VALUES_UNIQUE'] = array();
        $arSelect = Array("ID", "PROPERTY_" . $PROPERTY_ID);
        $arFilter = Array("IBLOCK_ID" => $_REQUEST['IBLOCK_ID'], "!PROPERTY_" . $PROPERTY_ID => false);
        $res = CIBlockElement::GetList(Array('NAME' => 'ASC'), $arFilter, false, false, $arSelect);
        while ($item = $res->Fetch()) {
            $result[$item["PROPERTY_" . $PROPERTY_ID . "_VALUE"]] ++;
            $_SESSION['PROP_VALUES'][$item["ID"]] = trim($item["PROPERTY_" . $PROPERTY_ID . "_VALUE"]);
        } 
        echo "<pre>";
        if($result) {
            echo "Все варианты значений и их количество:\r\n";
            foreach ($result as $text => $count) {
                echo "<b>" . $text . "</b> - " . $count . "\r\n";
                $_SESSION['PROP_VALUES_UNIQUE'][] = trim($text);
            }
        } else {
            ShowError("Ни одного значения не заполнено");
            break; 
        }
        echo "</pre>";
        ?> 
        <form method="POST">
            <input type="hidden" name="PROPERTY_ID" value="<?=$PROPERTY_ID;?>">
            <input type="hidden" name="IBLOCK_ID" value="<?=$_REQUEST['IBLOCK_ID'];?>">
            <input type="hidden" name="step" value="2">
            <input type="submit" name="submit" value="Шаг 2: Преобразовываем">
        </form>
        <?
        break;
    case 2:
        $PROPERTY_ID = $_REQUEST['PROPERTY_ID'];
        $IBLOCK_ID = $_REQUEST['IBLOCK_ID'];
        $arFields = array("PROPERTY_TYPE" => "L");
        foreach($_SESSION['PROP_VALUES_UNIQUE'] as $value) {
            $arFields["VALUES"][] = array(
                "VALUE" => $value,
                "DEF" => "N",
                "SORT" => "100"
           );   
        } 
        $ibp = new CIBlockProperty;
        if(!$ibp->Update($PROPERTY_ID, $arFields)) { 
            ShowError($ibp->LAST_ERROR);
        } else {
            $db_enum_list = CIBlockProperty::GetPropertyEnum($PROPERTY_ID, Array(), Array("IBLOCK_ID" => $IBLOCK_ID));
            while($ar_enum_list = $db_enum_list->GetNext()) {
                $propNameToId[$ar_enum_list['VALUE']] = $ar_enum_list['ID'];
            }
            foreach($_SESSION['PROP_VALUES'] as $elementId => $value) {
                CIBlockElement::SetPropertyValuesEx($elementId, $IBLOCK_ID, array($PROPERTY_ID => $propNameToId[$value]));
            } 
        } 
        echo 'Готово'; 
        break;
    default: ?>
        <script>
            $(function () {
                $(document).on('change', '[name="IBLOCK_ID"]', function () {
                    var iblockSelect = $(this);
                    var propsSelect = $('[name=PROPERTY_ID]');
                    $.getJSON('/bitrix/tools/iblocktools/getiblockprops.php',
                            {iblock_id: iblockSelect.val()},
                            function (propsList) {
                                propsSelect.html('');
                                if (propsList != null) {
                                    $.each(propsList, function () {
                                        propsSelect.append('<option value="' + this.ID + '">[' + this.ID + '] ' + this.NAME + '</option>');
                                    });
                                }
                            });
                });
                $('[name="IBLOCK_ID"]').change();
            });
        </script>
        <form method="POST">
            <? IblocktoolsFormsHelper::showIblockListSelect(); ?>
            <br><br>
            <select name="PROPERTY_ID"></select> 
            <br><br>
            <input type="hidden" name="step" value="1">
            <input type="submit" name="submit" value="Шаг 1: Просмотр всех вариантов значений">
        </form><?
        break;
}

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
