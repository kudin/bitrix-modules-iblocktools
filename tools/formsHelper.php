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

}
