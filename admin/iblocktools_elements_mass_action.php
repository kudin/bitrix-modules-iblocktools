<?php
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_before.php");
$APPLICATION->SetTitle('Массовые действия над полями элементов');
$module = 'iblocktools'; 
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/" . $module . "/include.php");
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_admin_after.php");

$fields = array('NAME', 'CODE', 'EXTERNAL_ID', 'SORT', 'PREVIEW_TEXT', 'DETAIL_TEXT', 'TAGS');

if ($_REQUEST['submit']) {
    
    $arSelect = Array("ID", "NAME", "IBLOCK_ID");
    $arFilter = Array("IBLOCK_ID"=>$_REQUEST['IBLOCK_ID']);
    $res = CIBlockElement::GetList(Array(), $arFilter, false, Array("nPageSize"=>5000), $arSelect);
    echo '<pre>';
    while($arFields = $res->GetNext()) {
          
  /*      $newField = htmlspecialchars_decode($arFields['NAME']);
         
        $el = new CIBlockElement;
        $result = $el->Update($arFields['ID'], array('NAME' => $newField));
         
        if($result) {
            echo 'Изменён элемент ' . $arFields['ID'] . "\n";
        } else {
            echo $el->LAST_ERROR . "\n";
        }
        */
    }
    echo '</pre>';
    
} else {
    ?> 
    <form method="POST" method="POST">
        <? IblocktoolsFormsHelper::showIblockListSelect(); ?>
        <br><br>
        <select name='field'><?
        foreach($fields as $field) { ?><option name="<?=$field;?>"><?=$field;?></option><? } ?></select>
        <br><br>
        <textarea name='code' style="width: 500px; height: 130px; resize: none;">$field = str_replace(' ', '_', $field);</textarea>
        <br><br>
        <input type="submit" name="submit" value="Предпросмотр"> 
    </form> 
    <?
}
require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/epilog_admin.php");
