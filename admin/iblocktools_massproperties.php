<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
$APPLICATION->SetTitle('Массовое создание свойств');
$module = 'iblocktools';
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $module . "/include.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");
if ($_REQUEST['submit']) {
    $res = CIBlock::GetList(
        Array(), 
        Array('TYPE' => $_REQUEST['IBLOCK_TYPE'])
    );
    while($ar_res = $res->Fetch()) {
        $arFields = Array(
            "NAME" => $_REQUEST['NAME'],
            "ACTIVE" => "Y",
            "SORT" => $_REQUEST['SORT'],
            "CODE" => $_REQUEST['CODE'],
            "PROPERTY_TYPE" => $_REQUEST['PROPERTY_TYPE'],
            "IBLOCK_ID" => $ar_res['ID'],
            "MULTIPLE" => $_REQUEST['MULTIPLE'] == 'Y' ? 'Y' : 'N'
        );
        $iblockproperty = new CIBlockProperty;
        $PropertyID = $iblockproperty->Add($arFields);
        if($PropertyID) {
            echo 'Добавлено в <b>' . $ar_res['NAME'] . '</b> (id: ' . $PropertyID . ')<br>';
        } else {
            echo '<span style="color:red">Ошибка добавления в инфоблок ' . $ar_res['NAME'] . ':</span><br>';
            echo $iblockproperty->LAST_ERROR;
            echo '<br>';
        }
    }
} else { ?>
    <form method="POST" method="POST">
        Имя свойства 
        <input type="text" name="NAME">
        <br><br>
        Код свойства 
        <input type="text" name="CODE">
        <br><br>
        Тип свойства 
        <select name="PROPERTY_TYPE">
        <? 
        $arTypes = array(           
            'S' => "строка",
            'N' => "число",
            'L' => "список",
            'F' => "файл", // 'G' => "привязка к разделу, 'E' => "привязка к элементу
        ); 
        foreach($arTypes as $code => $name) {
            ?><option value="<?=$code;?>"><?=$name;?></option><? } ?>
        </select>
        <br><br>
        <input type="checkbox" name="MULTIPLE" value="Y"> Множественное
        <br><br>    
        Сортировка <input type="text" value="500" name="SORT">
        <br><br>
        для всех инфоблоков типа <? IblocktoolsFormsHelper::showIblockTypesSelect(); ?>
        <br><br> 
        <input type="submit" name="submit" value="Создать"> 
    </form>
    <?
}
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");