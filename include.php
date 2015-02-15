<?php
 
if(defined('ADMIN_SECTION')) {

    \Bitrix\Main\Loader::registerAutoLoadClasses(
            "iblocktools", 
            array(
                    "IblocktoolsFormsHelper" => "tools/formsHelper.php",
            )
    );

}