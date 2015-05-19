<?php 

require($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");

if(!$USER->IsAdmin()) {
    die();
}

CModule::IncludeModule('iblock');
 
$properties = CIBlockProperty::GetList(
        array("sort" => "asc",
              "id" => "asc"), 
        array("ACTIVE" => "Y", 
              "IBLOCK_ID" => $_REQUEST['iblock_id'],
              "PROPERTY_TYPE" => "S")
        );
while ($prop_fields = $properties->GetNext()) {
    $result[] = array('ID' => $prop_fields["ID"], 
                      'NAME' => $prop_fields["NAME"]);
}

echo json_encode($result); 