<?php

class IblocktoolsFormsHelper {

    static function showIblockListSelect() {
        $res = CIBlock::GetList(array("ID" => "ASC"), array(), false); 
        echo '<select name="IBLOCK_ID">';
            while ($ar_res = $res->Fetch()) { 
                echo "<option value='{$ar_res['ID']}'>[{$ar_res['ID']}] {$ar_res['NAME']}</option>";
            }
        echo '</select>';
    }

    static function showIblockTypesSelect() {
        $res = CIBlockType::GetList();
        echo '<select name="IBLOCK_TYPE">';
            while ($ar_res = $res->Fetch()) { 
                echo "<option value='{$ar_res['ID']}'>{$ar_res['ID']}</option>";
            }
        echo '</select>';
    }

}
