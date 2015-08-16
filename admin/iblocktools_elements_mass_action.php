<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
$APPLICATION->SetTitle('Массовые действия над полями элементов');
$module = 'iblocktools'; 
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $module . "/include.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

$fields = array('NAME', 'CODE', 'EXTERNAL_ID', 'SORT', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'TAGS');

if ($_REQUEST['submit']) {
    $fieldName = $_REQUEST['field'];
    // никакой валидации! всё через жопу! будьте аккуратны
    $arSelect = Array("ID", "IBLOCK_ID", $fieldName);
    $arFilter = Array("IBLOCK_ID"=>$_REQUEST['IBLOCK_ID']);
    $res = CIBlockElement::GetList(Array('timestamp_x' => 'DESC'), $arFilter, false, Array("nPageSize"=>5000), $arSelect);
    echo '<pre>'; 
    $el = new CIBlockElement;
    while($arFields = $res->GetNext()) { 
        $field = $arFields[$fieldName];
        echo "<b>" . $arFields['ID'] . "</b>\n" . $field, "\n";  
        eval($_REQUEST['code']); // АМИНЬ !  
        echo $field, "\n"; 
        $result = $el->Update($arFields['ID'], array($fieldName => $field));
        if($result) {
            echo 'Изменён успешно';
        } else {
            echo $el->LAST_ERROR;
        } 
        echo "\n\n";
    }
    echo '</pre>';
    
} else {
    ?> 
        <table>
        <tr>
            <td width='50%' valign='top'> 
                <form method="POST" method="POST">
                    <? IblocktoolsFormsHelper::showIblockListSelect(); ?>
                    <br><br>
                    <select name='field'><? foreach ($fields as $field) { ?><option name="<?= $field; ?>"><?= $field; ?></option><? } ?></select>
                    <br><br>
                    Значение поля попадает в переменную $field<br>
                    <textarea name='code' style="width: 500px; height: 130px; resize: none;">$field = str_replace(' ', '_', $field);</textarea>
                    <br><br>
                    <input type="submit" name="submit" value="Готово"> 
                </form>  
            </td>
            <td width='50%' valign='top'>
                <pre>$field = htmlspecialchars_decode($field);

$field = strtoupper($field);
                </pre>
            </td>
        </tr>
    </table> 
    <?
}
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
