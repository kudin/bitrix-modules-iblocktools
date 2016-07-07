<?php

IncludeModuleLangFile(__FILE__);

class MassPropsActions {
	public static function OnAdminListDisplayHandler(&$list) {
		$bRightPage = ($GLOBALS['APPLICATION']->GetCurPage()=='/bitrix/admin/iblock_list_admin.php' ||
			       $GLOBALS['APPLICATION']->GetCurPage()=='/bitrix/admin/iblock_element_admin.php');

		if ($bRightPage && CModule::IncludeModule('iblock')) {
			CJSCore::Init(array('jquery'));
			$GLOBALS['APPLICATION']->AddHeadScript('/bitrix/tools/iblocktools/massprops/js/script.js');
			// select properties from iblock 
			$arProps = array();
			$properties = CIBlockProperty::GetList(Array("sort"=>"asc", "name"=>"asc"), array("ACTIVE"=>"Y", "IBLOCK_ID"=>$_REQUEST['IBLOCK_ID']));
			while ($prop_fields = $properties->GetNext()) {
				$arProps[$prop_fields["ID"]] = array('NAME'=>$prop_fields["NAME"], 'TYPE'=>$prop_fields['PROPERTY_TYPE']);
			}
			CJSCore::Init(array('file_input','fileinput','jquery'));
			$strIBlocksCp = '<div id="seprops_dest" style="display:none;"><table cellspacing="2" cellpadding="0" border="0"><tr><td valign="top">'.GetMessage('IBLOCKTOOLS_SELECT_PROP').':&nbsp;<select class="typeselect" onchange="se_props_selected(this.value, \''.$_REQUEST["IBLOCK_ID"].'\');" name="seprops_dest"><option value="null" disabled="disabled" selected="selected">&nbsp;</option>';
			$strIBlocksCp .= '<optgroup label="'.GetMessage('IBLOCKTOOLS_ELEMENT').'">';
				$strIBlocksCp .= '<option value="SORT">'.GetMessage('IBLOCKTOOLS_SORT').'</option>';
				$strIBlocksCp .= '<option value="PREVIEW_TEXT">'.GetMessage('IBLOCKTOOLS_PREVIEW_TEXT').'</option>';
				$strIBlocksCp .= '<option value="DETAIL_TEXT">'.GetMessage('IBLOCKTOOLS_DETAIL_TEXT').'</option>';
				$strIBlocksCp .= '<option value="DETAIL_PICTURE">'.GetMessage('IBLOCKTOOLS_DETAIL_PICTURE').'</option>';
				$strIBlocksCp .= '<option value="PREVIEW_PICTURE">'.GetMessage('IBLOCKTOOLS_PREVIEW_PICTURE').'</option>';
				$strIBlocksCp .= '<option value="PREVIEW_TEXT_EXT">'.GetMessage('IBLOCKTOOLS_PREVIEW_TEXT_EXT').'</option>';
				$strIBlocksCp .= '<option value="DETAIL_TEXT_EXT">'.GetMessage('IBLOCKTOOLS_DETAIL_TEXT_EXT').'</option>';
				$strIBlocksCp .= '<option value="TAGS">'.GetMessage('IBLOCKTOOLS_TAGS').'</option>';
			$strIBlocksCp .= '</optgroup>';

			if (CModule::IncludeModule("catalog") && CCatalog::GetByID($_REQUEST['IBLOCK_ID'])) {
				$strIBlocksCp .= '<optgroup label="'.GetMessage('IBLOCKTOOLS_CATALOG').'">';
					$strIBlocksCp .= '<option value="OTHER_PRICE">'.GetMessage('IBLOCKTOOLS_OTHER_PRICE').'</option>';
					$strIBlocksCp .= '<option value="UPDATE_PRICE">'.GetMessage('IBLOCKTOOLS_UPDATE_PRICE').'</option>';
					$strIBlocksCp .= '<option value="UPDATE_PRICE_MINUS">'.GetMessage('IBLOCKTOOLS_UPDATE_PRICE_MINUS').'</option>';
					$strIBlocksCp .= '<option value="PURCHASING_PRICE">'.GetMessage('IBLOCKTOOLS_PURCHASING_PRICE').'</option>';
					$strIBlocksCp .= '<option value="OTHER_QUANTITY">'.GetMessage('IBLOCKTOOLS_OTHER_QUANTITY').'</option>';
					$strIBlocksCp .= '<option value="MEASURE">'.GetMessage('IBLOCKTOOLS_MEASURE').'</option>';
					$strIBlocksCp .= '<option value="QUANTITY_TRACE">'.GetMessage('IBLOCKTOOLS_QUANTITY_TRACE').'</option>';
					$strIBlocksCp .= '<option value="CAN_BUY_ZERO">'.GetMessage('IBLOCKTOOLS_CAN_BUY_ZERO').'</option>';
					$strIBlocksCp .= '<option value="SUBSCRIBE">'.GetMessage('IBLOCKTOOLS_SUBSCRIBE').'</option>';
					$strIBlocksCp .= '<option value="SIZE">'.GetMessage('IBLOCKTOOLS_SIZE').'</option>';
					$strIBlocksCp .= '<option value="NDS">'.GetMessage('IBLOCKTOOLS_NDS').'</option>';

					if (CModule::IncludeModule("sale")) {
						$dbExtra = CExtra::GetList(Array("NAME"=>"asc"),Array(),false,false);
						$dbPersonalType = CSalePersonType::GetList(Array("SORT" => "ASC"), Array());
						if($dbExtra->SelectedRowsCount()>0 && $dbPersonalType->SelectedRowsCount()>0) {
							$strIBlocksCp .= '<option value="NACENKA">'.GetMessage('IBLOCKTOOLS_NACENKA').'</option>';
						}
					}
				$strIBlocksCp .= '</optgroup>';
			}

			if (count($arProps)>0) {
				$strIBlocksCp .= '<optgroup label="'.GetMessage('IBLOCKTOOLS_PROPS').'">';
			}
			foreach ($arProps as $id=>$value) {
				$strIBlocksCp .= '<option value="'.$id.'">'.$value['NAME'].'</option>';
			}
			if (count($arProps)>0) {
				$strIBlocksCp .= '</optgroup>';
			}
			$strIBlocksCp.='</select>
						</td>
						<td valign="top">
							<span id="seprops_dest_l2"></span>
						</td>
					</tr>
				</table>';
 
				$strIBlocksCp.='
			</div>
			<script type="text/javascript">
				var seprops_dest_el = document.getElementById("seprops_dest");
				var seprops_dest = seprops_dest_el.innerHTML;
				seprops_dest_el.innerHTML = "";
				var parent_el = seprops_dest_el.parentNode.parentNode.parentNode;
				var div = document.createElement("div");
				div.innerHTML = seprops_dest;
				div.id="seprops_dest2";
				div.style.display="none";
				div.style.padding="12px";
				parent_el.appendChild(div);
				seprops_dest_el.parentNode.removeChild(seprops_dest_el);
			</script>
			';

			$list->arActions['se_props'] = GetMessage('IBLOCKTOOLS_ACTION_PROPS');
			$list->arActions['se_props_chooser'] = array('type' => 'html', 'value' => $strIBlocksCp);
			$list->arActionsParams['select_onchange'] .= "BX('seprops_dest2').style.display = (this.value == 'se_props'? 'block':'none');";
		}  
	}

	public static function OnBeforePrologHandler() {
		$bRightPage = ($GLOBALS['APPLICATION']->GetCurPage()=='/bitrix/admin/iblock_list_admin.php' ||
			       $GLOBALS['APPLICATION']->GetCurPage()=='/bitrix/admin/iblock_element_admin.php');

		$bDoAction = false;
		if ($bRightPage && $_REQUEST['action']=='se_props_in_list' && strlen($_REQUEST['ID'])>0) {
				$bDoAction = true;
				$_REQUEST['action'] = 'se_props';
				$_REQUEST['seprops_dest'] = $_REQUEST['IBLOCK_ID'];
				$_REQUEST['ID'] = array($_REQUEST['ID']);
		}

		if ($bRightPage && check_bitrix_sessid() &&
			($_SERVER['REQUEST_METHOD']=='POST' || $bDoAction) && CModule::IncludeModule('iblock') &&
			$_REQUEST['action']=='se_props' && isset($_REQUEST['seprops_dest']) && CIBlock::GetPermission($_REQUEST['IBLOCK_ID'])>='W'
		)
		{ 
			$el = new CIBlockElement();

			if (empty($_REQUEST['ID']) && $_REQUEST['action_target']=='selected') { // dlya vseh
				$_REQUEST['ID']=array();
				$dbrFProps = CIBlockProperty::GetList(
					array("SORT"=>"ASC", "NAME"=>"ASC"),
					array("ACTIVE"=>"Y", "CHECK_PERMISSIONS"=>"N", "IBLOCK_ID"=>$_REQUEST['IBLOCK_ID'])
				);

				$arProps = Array();
				while($arProp = $dbrFProps->GetNext()) {
					if (strlen($arProp["USER_TYPE"])>0)
						$arUserType = CIBlockProperty::GetUserType($arProp["USER_TYPE"]);
					else
						$arUserType = array();

					$arProp["PROPERTY_USER_TYPE"] = $arUserType;

					$arProps[] = $arProp;
				}

				$arFilterFields = Array (
					"find_name",
					"find_section_section",
					"find_id_1", "find_id_2",
					"find_timestamp_1",	"find_timestamp_2",
					"find_code",
					"find_external_id",
					"find_modified_by",	"find_modified_user_id",
					"find_created_from", "find_created_to",
					"find_created_by", "find_created_user_id",
					"find_date_active_from_from", "find_date_active_from_to",
					"find_date_active_to_from", "find_date_active_to_to",
					"find_active",
					"find_intext",
					"find_status", "find_status_id",
					"find_tags", "find_el","find_el_id_start","find_el_id_end",
					"find_el_name", 
					"find_el_intext",
					"find_el_code",
					"find_el_tags",
					"find_el_active",
					"find_el_external_id",
					"find_el_date_active_to_from",
					"find_el_date_active_from_to",
					"find_el_timestamp_1",
					"find_el_timestamp_2",
					"find_el_modified_by",
					"find_el_modified_user_id",
					"find_created_from",
					"find_created_to",
					"find_created_by",
					"find_created_user_id",
					"find_date_active_to_from",
					"find_date_active_to_to"
					
				);

				$section_id = intval($find_section_section);
				foreach ($arFilterFields as $key => $value) {
					if (isset($_REQUEST[$value])) {
						if (defined('BX_UTF')) {
							$$value = $_REQUEST[$value];
						} else {
							$$value = CharsetConverter::ConvertCharset($_REQUEST[$value], 'utf-8', 'windows-1251');
						}
					}
				}
				//$find_section_section = $section_id;
				if ($find_section_section=='-1') {
					$find_section_section='';
				}

				$arFilter = Array(
					"IBLOCK_ID" => $_REQUEST['IBLOCK_ID'],
					"?NAME" => ($find_name ? $find_name  : ($find_el ?  $find_el : $find_el_name)),
					"SECTION_ID" => $find_section_section,
					"ID_1" => ($find_id_1 ? $find_id_1 : $find_el_id_start),
					"ID_2" => ($find_id_2 ? $find_id_2 : $find_el_id_end),
					"TIMESTAMP_X_1" => ($find_timestamp_1 ? $find_timestamp_1 : $find_el_timestamp_1),
					"TIMESTAMP_X_2" => ($find_timestamp_2 ? $find_timestamp_2 : $find_el_timestamp_2),
					"CODE" => ($find_code ? $find_code : $find_el_code),
					"EXTERNAL_ID" => ($find_external_id ? $find_external_id : $find_el_external_id),
					"MODIFIED_BY" => ($find_modified_by ? $find_modified_by : $find_el_modified_by),
					"MODIFIED_USER_ID" => ($find_modified_user_id ? $find_modified_user_id : $find_el_modified_user_id),
					"DATE_CREATE_1" => ($find_created_from ? $find_created_from : $find_el_created_from),
					"DATE_CREATE_2" => ($find_created_to ? $find_created_to : $find_el_created_to),
					"CREATED_BY" => ($find_created_by ? $find_created_by : $find_el_created_by),
					"CREATED_USER_ID" => ($find_created_user_id ? $find_created_user_id : $find_el_created_user_id),
					"DATE_ACTIVE_FROM_1" => ($find_date_active_from_from ? $find_date_active_from_from : $find_el_date_active_to_from),
					"DATE_ACTIVE_FROM_2" => ($find_date_active_from_to ? $find_date_active_from_to : $find_el_date_active_from_to),
					"DATE_ACTIVE_TO_1" => ($find_date_active_to_from ? $find_date_active_to_from : $find_el_date_active_to_from),
					"DATE_ACTIVE_TO_2" => ($find_date_active_to_to ? $find_date_active_to_to : $find_el_date_active_to_to),
					"ACTIVE" => ($find_active ? $find_active : $find_el_active),
					"DESCRIPTION" => ($find_intext ? $find_intext : $find_el_intext),
					"WF_STATUS" => $find_status==""?$find_status_id:$find_status,
					"?TAGS" => ($find_tags ? $find_tags : $find_el_tags),
				);

				foreach ($arFilter as $key => $value) {
					if (!$arFilter[$key]) unset ($arFilter[$key]);
				}

				foreach($arProps as $arProp)
				{
					if($arProp["FILTRABLE"]=="Y" && $arProp["PROPERTY_TYPE"]!="F")
					{
						$value = $_REQUEST["find_el_property_".$arProp["ID"]];
						if(array_key_exists("AddFilterFields", $arProp["PROPERTY_USER_TYPE"]))
						{
							call_user_func_array($arProp["PROPERTY_USER_TYPE"]["AddFilterFields"], array(
								$arProp,
								array("VALUE" => "find_el_property_".$arProp["ID"]),
								&$arFilter,
								&$filtered,
							));
						}
						elseif(strlen($value) || is_array($value))
						{
							if($value === "NOT_REF")
								$value = false;
							$arFilter["?PROPERTY_".$arProp["ID"]] = $value;
						}
					}
				}

				$dbEl = CIBlockElement::GetList(array(), $arFilter, FALSE, FALSE, array('IBLOCK_ID', 'ID'));
				while ($arEl = $dbEl->GetNext()) {
					$_REQUEST['ID'][]=$arEl['ID'];
				}
			} else {
				$ID=array();
				foreach ($_REQUEST['ID'] as $key => $value) {
					if (strpos($value, 'E')!==FALSE) {
						preg_match('/E([0-9]+)/', $value, $matches);
						if ($matches[1]) {
							$ID[]=$matches[1];
						}
					} else {
						$ID[]=$value;
					}
				}
				$_REQUEST['ID']=$ID;
			}

			if (!empty($_REQUEST['ID'])) {
				
				//echo '<pre>'; print_r($_REQUEST); echo '</pre>';
				//echo '<pre>'; print_r($_FILES); echo '</pre>';
				########## приведение сво-ва в записываемый формат ##########
				if ($_REQUEST['seprops_dest_type']=='S:HTML') { // HTML/text
					$tmp = array();

					// perebiraem $_REQUEST v poiske text oblastey
					foreach ($_REQUEST as $key => $value) {
						if (strpos($key, 'PROP_'.$_REQUEST['seprops_dest'])!==FALSE) {
							$arr = explode('__', $key); // PROP_82__n0__VALUE__TEXT_
							$arr[3] = str_replace('_', '', $arr[3]);
							$tmp[$arr[1]][$arr[2]][$arr[3]] = $value;
						}
					}

					$_REQUEST['PROP'][$_REQUEST['seprops_dest']]=$tmp;
				} elseif ($_REQUEST['seprops_dest_type']=='F') {
					// poluchenie CODE svo-va po ID
					$dbProp = CIBlockProperty::GetByID($_REQUEST['seprops_dest'], FALSE, FALSE);
					$arProp = $dbProp->GetNext();

					if ($arProp['MULTIPLE']=='Y' && $_REQUEST['seprops_multiple']=='CLEAR' || $arProp['MULTIPLE']=='N') { // если сво-во типа ‘айл обновл€ют - сначала удал€ем старые файлы из него
						$dbEl = CIBlockElement::GetList(array(), array('ID'=>$_REQUEST['ID'], 'IBLOCK_ID'=>$_REQUEST['IBLOCK_ID']), FALSE, FALSE, array('IBLOCK_ID', 'ID'));
						while ($arEl = $dbEl->GetNext()) {
							CIBlockElement::SetPropertyValuesEx($arEl['ID'], $arEl['IBLOCK_ID'], array($_REQUEST['seprops_dest'] => Array ("VALUE" => array("del" => "Y"))));
						}
					}
					if(is_array($_REQUEST['PROP'][$_REQUEST['seprops_dest']])) {
						foreach($_REQUEST['PROP'][$_REQUEST['seprops_dest']] as $key=>$val) {
							$val['tmp_name'] = $_SERVER["DOCUMENT_ROOT"].$val['tmp_name']; 
							$arFiles[] = array("VALUE" => $val ,"DESCRIPTION"=>$val['name']);
						}
					} else {
						foreach ($_FILES['PROP']["tmp_name"][$_REQUEST['seprops_dest']] as $key=>$val) {
							$arFiles[$key] = CFile::MakeFileArray($val);
							$arFiles[$key]['name']=$_FILES['PROP']['name'][$_REQUEST['seprops_dest']][$key];
						}
					}
					$_REQUEST['PROP'][$_REQUEST['seprops_dest']] = $arFiles;
				} elseif ($_REQUEST['seprops_dest_type']=='S:DateTime' || $_REQUEST['seprops_dest_type']=='E:EList') {
					$tmp=array();
					foreach ($_REQUEST['PROP'][$_REQUEST['seprops_dest']] as $key => $value) {
						$tmp[]=$value;
					}
					$_REQUEST['PROP'][$_REQUEST['seprops_dest']] = $tmp;
				} elseif ($_REQUEST['seprops_dest_type']=='S:video') {
					foreach ($_FILES['PROP']['tmp_name'][$_REQUEST['seprops_dest']] as $nNUM => $value) {
						if ($value['VALUE']['FILE']!='') {
							$arFile = CFile::MakeFileArray($value['VALUE']['FILE']);
							$arFile['name'] = $_FILES['PROP']['name'][$_REQUEST['seprops_dest']][$nNUM]['VALUE']['FILE'];
							$arFileID = CFile::SaveFile($arFile, 'iblock');
							$_REQUEST['PROP'][$_REQUEST['seprops_dest']][$nNUM]['VALUE']['PATH'] = CFile::GetPath($arFileID);
						}
					}
				}
				############################################################

				foreach ($_REQUEST['ID'] as $eID) {
					$ID = intval($eID);
					if ($_REQUEST['seprops_dest']=='PREVIEW_TEXT' || $_REQUEST['seprops_dest']=='DETAIL_TEXT') {
						$el = new CIBlockElement;
						$arF = array($_REQUEST['seprops_dest']=>$_REQUEST[$_REQUEST['seprops_dest']], $_REQUEST['seprops_dest']."_TYPE"=>$_REQUEST[$_REQUEST['seprops_dest']."_TYPE"]);
						$res = $el->Update($ID, $arF);
					} elseif ($_REQUEST['seprops_dest']=='PREVIEW_TEXT_EXT' || $_REQUEST['seprops_dest']=='DETAIL_TEXT_EXT') {
						$arFilter = array('ID'=>$ID);
						$arSelect = array('IBLOCK_ID', 'ID');
						if ($_REQUEST['seprops_dest']=='PREVIEW_TEXT_EXT') {
							$arSelect[]='PREVIEW_TEXT';
						} elseif ($_REQUEST['seprops_dest']=='DETAIL_TEXT_EXT') {
							$arSelect[]='DETAIL_TEXT';
						}
						$dbEl = CIBlockElement::GetList(array('SORT'=>'ASC'), $arFilter, FALSE, FALSE, $arSelect);
						if ($arEl = $dbEl->GetNext()) {
							$arF = array();
							if (strlen($arEl['~PREVIEW_TEXT'])<=0) $arEl['~PREVIEW_TEXT']='';
							if (strlen($arEl['PREVIEW_TEXT_TYPE'])<=0) $arEl['PREVIEW_TEXT_TYPE']='text';
							if (strlen($arEl['~DETAIL_TEXT'])<=0) $arEl['~DETAIL_TEXT']='';
							if (strlen($arEl['DETAIL_TEXT_TYPE'])<=0) $arEl['DETAIL_TEXT_TYPE']='text';

							if ($_REQUEST['seprops_dest']=='PREVIEW_TEXT_EXT') {
								$arF['PREVIEW_TEXT']=($_REQUEST['PREVIEW_TEXT_EXT_TYPE']=='start') ? $_REQUEST['PREVIEW_TEXT_EXT_TEXT'].$arEl['~PREVIEW_TEXT'] : $arEl['~PREVIEW_TEXT'].$_REQUEST['PREVIEW_TEXT_EXT_TEXT'] ;
								$arF['PREVIEW_TEXT_TYPE']=$arEl['PREVIEW_TEXT_TYPE'];
							} elseif ($_REQUEST['seprops_dest']=='DETAIL_TEXT_EXT') {
								$arF['DETAIL_TEXT']=($_REQUEST['DETAIL_TEXT_EXT_TYPE']=='start') ? $_REQUEST['DETAIL_TEXT_EXT_TEXT'].$arEl['~DETAIL_TEXT'] : $arEl['~DETAIL_TEXT'].$_REQUEST['DETAIL_TEXT_EXT_TEXT'] ;
								$arF['DETAIL_TEXT_TYPE']=$arEl['DETAIL_TEXT_TYPE'];
							}

							$res = $el->Update($ID, $arF);
						}
					} elseif ($_REQUEST['seprops_dest']=='DETAIL_PICTURE' || $_REQUEST['seprops_dest']=='PREVIEW_PICTURE') {
						$el = new CIBlockElement;
						if ($_FILES[$_REQUEST['seprops_dest']] || $_REQUEST[$_REQUEST['seprops_dest']]) {
							
							if($_FILES[$_REQUEST['seprops_dest']]){
								$ress = CIBlockElement::GetList(Array("SORT"=>"ASC"), Array("ID"=>$ID),false,false,Array("IBLOCK_ID","ID",$_REQUEST['seprops_dest'],"NAME"));
								while($obj = $ress->GetNext()) {
									if(strlen($_REQUEST[$_REQUEST['seprops_dest']."_descr"])>0) {
										$_FILES[$_REQUEST['seprops_dest']]['description'] = $_REQUEST[$_REQUEST['seprops_dest']."_descr"];
									} else {
										$_FILES[$_REQUEST['seprops_dest']]["description"] = $obj["NAME"];
									}
								}
								$arF = array($_REQUEST['seprops_dest']=>$_FILES[$_REQUEST['seprops_dest']]);
							}
							else {
								$arF = array($_REQUEST['seprops_dest']=>CFile::MakeFileArray($_SERVER["DOCUMENT_ROOT"].$_REQUEST[$_REQUEST['seprops_dest']]));
								$arF[$_REQUEST['seprops_dest']]['description'] = $_REQUEST[$_REQUEST['seprops_dest']."_descr"];
							}
							
							$res = $el->Update($ID, $arF, false, false, true);
						} else{
							$res = CIBlockElement::GetList(Array("SORT"=>"ASC"), Array("ID"=>$ID),false,false,Array("IBLOCK_ID","ID",$_REQUEST['seprops_dest'],"NAME"));
							while($ob = $res->GetNextElement()) {
								
								if(strlen($_REQUEST[$_REQUEST['seprops_dest']."_descr"])>0) {
									CFile::UpdateDesc($ob[$_REQUEST['seprops_dest']], $_REQUEST[$_REQUEST['seprops_dest']."_descr"]);
								} else {
									$_REQUEST[$_REQUEST['seprops_dest']."_descr"]= $ob["NAME"];
									CFile::UpdateDesc($ob[$_REQUEST['seprops_dest']], $_REQUEST[$_REQUEST['seprops_dest']."_descr"]);
								}
							}
						}

					} elseif ($_REQUEST['seprops_dest']=='OTHER_PRICE' && CModule::IncludeModule('catalog')) {
						foreach ($_REQUEST['OTHER_PRICE'] as $priceID => $value) {
							if (floatval($value['PRICE'])>0 && strlen($value['CURRENCY'])>0) {
								$arFields = Array(
									"PRODUCT_ID" => $ID,
									"CATALOG_GROUP_ID" => $priceID,
									"PRICE" => $value['PRICE'],
									"CURRENCY" => $value['CURRENCY']
								);

								$res = CPrice::GetList(array(), array("PRODUCT_ID" => $ID, "CATALOG_GROUP_ID" => $priceID));

								if ($arr = $res->Fetch()) {
								    CPrice::Update($arr["ID"], $arFields);
								} else {
								    CPrice::Add($arFields);
								}
							}
						}
					} elseif ($_REQUEST['seprops_dest']=='OTHER_QUANTITY' && CModule::IncludeModule('catalog') && $_REQUEST['OTHER_QUANTITY']!='') {
						$arFileds = array('QUANTITY' => $_REQUEST['OTHER_QUANTITY']);
						if ($_REQUEST['OTHER_QUANTITY_TRACE']=='Y')  {
							$arFileds['QUANTITY_TRACE']='Y';
						} elseif ($_REQUEST['OTHER_QUANTITY_TRACE']=='N') {
							$arFileds['QUANTITY_TRACE']='N';
						} else {
							unset($arFileds['QUANTITY_TRACE']);
						}

						$arCatalogProduct = CCatalogProduct::GetByID($ID);
						if ($arCatalogProduct) {
							$resUpdate = CCatalogProduct::Update($ID, $arFileds);
						} else {
							$arFileds['ID'] = $ID;
							$resAdd = CCatalogProduct::Add($arFileds);
						}
					} elseif($_REQUEST['seprops_dest']=='SORT') {
						$el = new CIBlockElement;
						$res = $el->Update($ID, Array("SORT"=>$_REQUEST[$_REQUEST['seprops_dest']]));
					} elseif($_REQUEST['seprops_dest']=='TAGS') {
						$el = new CIBlockElement;
						$res = $el->Update($ID, Array("TAGS"=>$_REQUEST[$_REQUEST['seprops_dest']]));
					} elseif($_REQUEST['seprops_dest']=='NDS' && CModule::IncludeModule('catalog')) {
						if ($_REQUEST['NDS']) {
							$nds = 'Y';
						} else {
							$nds = 'N';
						}
						CCatalogProduct::Update($ID, Array("VAT_INCLUDED"=> $nds));
					} elseif ($_REQUEST['seprops_dest']=='PURCHASING_PRICE' && floatval($_REQUEST['PURCHASING_PRICE'])>0 && CModule::IncludeModule('catalog')) { // закупочна€ цена
						CCatalogProduct::Update($ID, Array("PURCHASING_PRICE"=>$_REQUEST['PURCHASING_PRICE'], 'PURCHASING_CURRENCY'=>$_REQUEST['PURCHASING_CURRENCY']));
					} elseif ($_REQUEST['seprops_dest']=='MEASURE' && intval($_REQUEST['MEASURE'])>0 && CModule::IncludeModule('catalog')) { // единица измерени€
						CCatalogProduct::Update($ID, Array("MEASURE"=>$_REQUEST['MEASURE']));

						$dbMeasureRating = CCatalogMeasureRatio::getList(array(), array('PRODUCT_ID'=>$ID));
						if ($arMeasureRation=$dbMeasureRating->Fetch()) {
							CCatalogMeasureRatio::update($arMeasureRation['ID'], array('RATIO'=>$_REQUEST['MEASURE_RATIO']));
						} 
					} elseif ($_REQUEST['seprops_dest']=='SIZE' && count($_REQUEST['SIZE'])>0 && CModule::IncludeModule('catalog')) { // размеры товара
						CCatalogProduct::Update($ID, $_REQUEST['SIZE']);
					} elseif ($_REQUEST['seprops_dest']=='CAN_BUY_ZERO' && count($_REQUEST['USE_ZERO_STORE_SUBSCRIBE'])>0 && CModule::IncludeModule('catalog')) { // параметры товара
						CCatalogProduct::Update($ID, Array("CAN_BUY_ZERO"=>$_REQUEST['USE_ZERO_STORE_SUBSCRIBE']));
					} elseif ($_REQUEST['seprops_dest']=='QUANTITY_TRACE' && count($_REQUEST['USE_ZERO_STORE_SUBSCRIBE'])>0 && CModule::IncludeModule('catalog')) { // параметры товара
						CCatalogProduct::Update($ID, Array("QUANTITY_TRACE"=>$_REQUEST['USE_ZERO_STORE_SUBSCRIBE']));
					} elseif ($_REQUEST['seprops_dest']=='SUBSCRIBE' && count($_REQUEST['USE_ZERO_STORE_SUBSCRIBE'])>0 && CModule::IncludeModule('catalog')) { // параметры товара
						CCatalogProduct::Update($ID, Array("SUBSCRIBE"=>$_REQUEST['USE_ZERO_STORE_SUBSCRIBE']));
					} elseif($_REQUEST['PRICE_TYPE'] && ($_REQUEST['NACENKA'] || $_REQUEST['NACENKA']==0) && $_REQUEST['seprops_dest']=='NACENKA') {
						CModule::IncludeModule('catalog');
						$arFields = array(
							"PRODUCT_ID" => $ID,
							"CATALOG_GROUP_ID" => $_REQUEST['PRICE_TYPE'],
							"PRICE" => 0,
							"CURRENCY" => ($_REQUEST['CURRENCY_DEFAULT'] ? $_REQUEST['CURRENCY_DEFAULT'] : "RUB"),
							"EXTRA_ID" => $_REQUEST['NACENKA'],
							"QUANTITY_FROM" => false,
							"QUANTITY_TO" => false
						);
						
						$ress = CPrice::GetList(
							array(),
							array(
								"PRODUCT_ID" => $ID,
								"CATALOG_GROUP_ID" => $_REQUEST['PRICE_TYPE']
							)
						);
						if ($arr = $ress->Fetch()) {
							$obPrice = new CPrice();
							if($_REQUEST['NACENKA']=='0'){
								$obPrice->Delete($arr["ID"]);
							} else {
								$obPrice->Update($arr["ID"],$arFields,true);
							}
						} else {
							$obPrice = new CPrice();
							$obPrice->Add($arFields,true);
						}
					} elseif($_REQUEST['PRICE_TYPE'] && $_REQUEST['UPDATE_PRICE_PERCENT']  && $_REQUEST['seprops_dest']=='UPDATE_PRICE') {

						CModule::IncludeModule('catalog');
						$ress = CPrice::GetList(
							array(),
							array(
								"PRODUCT_ID" => $ID,
								"CATALOG_GROUP_ID" => $_REQUEST['PRICE_TYPE']
							)
						);
						if ($arr = $ress->Fetch()) {
							$arFields = array(
								"PRODUCT_ID" => $ID,
								"CATALOG_GROUP_ID" => $_REQUEST['PRICE_TYPE'],
								"PRICE" => $arr["PRICE"] + (($arr["PRICE"]*$_REQUEST['UPDATE_PRICE_PERCENT'])/100),
								"CURRENCY" => ($_REQUEST['CURRENCY_DEFAULT'] ? $_REQUEST['CURRENCY_DEFAULT'] : CCurrency::GetBaseCurrency()),
								"QUANTITY_FROM" => false,
								"QUANTITY_TO" => false
							);
							$obPrice = new CPrice();
							$obPrice->Update($arr["ID"],$arFields,false);
						} else {
							$ar_res = CPrice::GetBasePrice($ID, false, false);
							$arFields = array(
								"PRODUCT_ID" => $ID,
								"CATALOG_GROUP_ID" => $_REQUEST['PRICE_TYPE'],
								"PRICE" => $ar_res["PRICE"] + (($ar_res["PRICE"]*$_REQUEST['UPDATE_PRICE_PERCENT'])/100),
								"CURRENCY" => ($_REQUEST['CURRENCY_DEFAULT'] ? $_REQUEST['CURRENCY_DEFAULT'] : CCurrency::GetBaseCurrency()),
								"QUANTITY_FROM" => false,
								"QUANTITY_TO" => false
							);
							$obPrice = new CPrice();
							$obPrice->Add($arFields,true);
						}

						// мен€ем цену у торговых предложений
						if ($_REQUEST['UPDATE_PRICE_SKU']=='Y') {
							$dbSku = CIBlockElement::GetList(array('SORT'=>'ASC'), array('PROPERTY_CML2_LINK'=>$ID), FALSE, FALSE, array('IBLOCK_ID', 'ID'));
							while ($arSku = $dbSku->GetNext()) {
								$ress = CPrice::GetList(array(), array("PRODUCT_ID" => $arSku['ID'], "CATALOG_GROUP_ID" => $_REQUEST['PRICE_TYPE']));
								if ($arr = $ress->Fetch()) {
									$arFields = array(
										"PRODUCT_ID" => $arSku['ID'],
										"CATALOG_GROUP_ID" => $_REQUEST['PRICE_TYPE'],
										"PRICE" => $arr["PRICE"] + (($arr["PRICE"]*$_REQUEST['UPDATE_PRICE_PERCENT'])/100),
										"CURRENCY" => ($_REQUEST['CURRENCY_DEFAULT'] ? $_REQUEST['CURRENCY_DEFAULT'] : CCurrency::GetBaseCurrency()),
										"QUANTITY_FROM" => false,
										"QUANTITY_TO" => false
									);
									$obPrice = new CPrice();
									$obPrice->Update($arr["ID"],$arFields,true);
								} else {
									$ar_res = CPrice::GetBasePrice($arSku['ID'], false, false);
									$arFields = array(
										"PRODUCT_ID" => $arSku['ID'],
										"CATALOG_GROUP_ID" => $_REQUEST['PRICE_TYPE'],
										"PRICE" => $ar_res["PRICE"] + (($ar_res["PRICE"]*$_REQUEST['UPDATE_PRICE_PERCENT'])/100),
										"CURRENCY" => ($_REQUEST['CURRENCY_DEFAULT'] ? $_REQUEST['CURRENCY_DEFAULT'] : CCurrency::GetBaseCurrency()),
										"QUANTITY_FROM" => false,
										"QUANTITY_TO" => false
									);
									$obPrice = new CPrice();
									$obPrice->Add($arFields,true);
								}
							}
						}
					} elseif($_REQUEST['PRICE_TYPE'] && $_REQUEST['UPDATE_PRICE_PERCENT_MINUS']  && $_REQUEST['seprops_dest']=='UPDATE_PRICE_MINUS') {
					//<price
						CModule::IncludeModule('catalog');
						$ress = CPrice::GetList(
							array(),
							array(
								"PRODUCT_ID" => $ID,
								"CATALOG_GROUP_ID" => $_REQUEST['PRICE_TYPE']
							)
						);
						if ($arr = $ress->Fetch()) {
							$arFields = array(
								"PRODUCT_ID" => $ID,
								"CATALOG_GROUP_ID" => $_REQUEST['PRICE_TYPE'],
								"PRICE" => $arr["PRICE"] - (($arr["PRICE"]*$_REQUEST['UPDATE_PRICE_PERCENT_MINUS'])/100),
								"CURRENCY" => ($_REQUEST['CURRENCY_DEFAULT'] ? $_REQUEST['CURRENCY_DEFAULT'] : CCurrency::GetBaseCurrency()),
								"QUANTITY_FROM" => false,
								"QUANTITY_TO" => false
							);
							$obPrice = new CPrice();
							$obPrice->Update($arr["ID"],$arFields,false);
						} else {
							$ar_res = CPrice::GetBasePrice($ID, false, false);
							$arFields = array(
								"PRODUCT_ID" => $ID,
								"CATALOG_GROUP_ID" => $_REQUEST['PRICE_TYPE'],
								"PRICE" => $ar_res["PRICE"] - (($ar_res["PRICE"]*$_REQUEST['UPDATE_PRICE_PERCENT_MINUS'])/100),
								"CURRENCY" => ($_REQUEST['CURRENCY_DEFAULT'] ? $_REQUEST['CURRENCY_DEFAULT'] : CCurrency::GetBaseCurrency()),
								"QUANTITY_FROM" => false,
								"QUANTITY_TO" => false
							);
							$obPrice = new CPrice();
							$obPrice->Add($arFields,true);
						}

						// мен€ем цену у торговых предложений
						if ($_REQUEST['UPDATE_PRICE_SKU_MINUS']=='Y') {
							$dbSku = CIBlockElement::GetList(array('SORT'=>'ASC'), array('PROPERTY_CML2_LINK'=>$ID), FALSE, FALSE, array('IBLOCK_ID', 'ID'));
							while ($arSku = $dbSku->GetNext()) {
								$ress = CPrice::GetList(array(), array("PRODUCT_ID" => $arSku['ID'], "CATALOG_GROUP_ID" => $_REQUEST['PRICE_TYPE']));
								if ($arr = $ress->Fetch()) {
									$arFields = array(
										"PRODUCT_ID" => $arSku['ID'],
										"CATALOG_GROUP_ID" => $_REQUEST['PRICE_TYPE'],
										"PRICE" => $arr["PRICE"] - (($arr["PRICE"]*$_REQUEST['UPDATE_PRICE_PERCENT_MINUS'])/100),
										"CURRENCY" => ($_REQUEST['CURRENCY_DEFAULT'] ? $_REQUEST['CURRENCY_DEFAULT'] : CCurrency::GetBaseCurrency()),
										"QUANTITY_FROM" => false,
										"QUANTITY_TO" => false
									);
									$obPrice = new CPrice();
									$obPrice->Update($arr["ID"],$arFields,true);
								} else {
									$ar_res = CPrice::GetBasePrice($arSku['ID'], false, false);
									$arFields = array(
										"PRODUCT_ID" => $arSku['ID'],
										"CATALOG_GROUP_ID" => $_REQUEST['PRICE_TYPE'],
										"PRICE" => $ar_res["PRICE"] - (($ar_res["PRICE"]*$_REQUEST['UPDATE_PRICE_PERCENT_MINUS'])/100),
										"CURRENCY" => ($_REQUEST['CURRENCY_DEFAULT'] ? $_REQUEST['CURRENCY_DEFAULT'] : CCurrency::GetBaseCurrency()),
										"QUANTITY_FROM" => false,
										"QUANTITY_TO" => false
									);
									$obPrice = new CPrice();
									$obPrice->Add($arFields,true);
								}
							}
						}
						//end
					} else {
						$arPropVal = $_REQUEST['PROP'][$_REQUEST['seprops_dest']];
						// если надо добавл€ть, а не замен€ть значени€ - получаем уже установленные значени€ в элементе
						// файлы удал€ютс€ отдельно (вверху), поэтому таких манипул€ций с ними проводить не надо
						if ($_REQUEST['seprops_multiple']=='UPDATE' && $_REQUEST['seprops_dest_type']!='F') {
							$arPropValOLD = array();
							$i = count($_REQUEST['PROP'][$_REQUEST['seprops_dest']]);
							$dbPropOLD = CIBlockElement::GetProperty($_REQUEST['IBLOCK_ID'], $ID, array("sort" => "asc"), Array("ID"=>$_REQUEST['seprops_dest']));
							while($arPropOLD = $dbPropOLD->Fetch()) {
								//echo '<pre>'; print_r($arPropOLD); echo '</pre>';
								if (isset($_REQUEST['PROP'][$_REQUEST['seprops_dest']]['n0'])) {
									$arPropValOLD['n'.$i]['VALUE'] = $arPropOLD['VALUE'];
									$i++;
								} else {
									$arPropValOLD[]=$arPropOLD['VALUE'];
								}
							}
							$arPropVal = array_merge($_REQUEST['PROP'][$_REQUEST['seprops_dest']], $arPropValOLD);
						}
						
						if ($_REQUEST['seprops_multiple']=='UPDATE' && $_REQUEST['seprops_dest_type']=='F') {
							$dbPropOLD = CIBlockElement::GetProperty($_REQUEST['IBLOCK_ID'], $ID, array("sort" => "asc"), Array("ID"=>$_REQUEST['seprops_dest']));
							$i=0;
							while($arPropOLD = $dbPropOLD->Fetch()) {
								//echo '<pre>'; print_r($arPropOLD); echo '</pre>';
								$arPropValOLD[$i]['VALUE']= CFile::MakeFileArray($arPropOLD['VALUE']);
								$arPropValOLD[$i]['DESCRIPTION'] = $arPropOLD['DESCRIPTION'];
								$i++;
							}
							$arPropVal = array_merge($_REQUEST['PROP'][$_REQUEST['seprops_dest']], $arPropValOLD);
						}
						
						if($_REQUEST['seprops_dest_type']=='F' && $arProp['MULTIPLE']=='Y' ) {
							$res = CIBlockProperty::GetByID($_REQUEST['seprops_dest'], false, false);
							if($ar_res = $res->GetNext())
							  $PropCode = $ar_res['CODE'];
							  //var_dump($arPropVal);
							$res = CIBlockElement::SetPropertyValuesEx($ID, $_REQUEST['IBLOCK_ID'], array($PropCode => $arPropVal));
						} else { 
							$res = CIBlockElement::SetPropertyValueCode($ID, $_REQUEST['seprops_dest'], $arPropVal);
						}	
					}
				}
				unset($_REQUEST['action']);
			}
		}
  
	}
 
} 