<?php

$aMenu[] = array(
    "parent_menu" => "global_menu_services",
    "sort" => "5",
    "text" => 'Настройки инфоблоков',
    "title" => 'Настройки инфоблоков',
    "icon" => "iblocktools_menu_icon",
    "page_icon" => "iblocktools_menu_icon",
    "items_id" => "iblocktools_main",
    "url" => "iblocktools.php",
    "items" => array(
        array(
            "text" => 'Свойства элементов',
            "title" => 'Свойства элементов',
            "items_id" => "iblocktools_props",
            "items" => array(
                array(
                    "text" => 'Строковые свойства в список',
                    "title" => 'Строковые свойства в список',
                    "items_id" => "iblocktools_props",
                    "url" => "iblocktools_strptops_to_list.php",
                )
            )
        )
    )
);

return $aMenu;
