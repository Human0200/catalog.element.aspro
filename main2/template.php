<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?php if (!empty($arResult['PROPERTIES']['PREVIEW_VIDEO_IMG']['VALUE'])): ?>
<script>
  window.__videoPoster = "<?=CUtil::JSEscape(CFile::GetPath($arResult['PROPERTIES']['OBLOJKA_DLYA_VIDEO']['VALUE']))?>";
</script>
<?php endif; ?>

<? //echo '<!--*******'.print_r($arResult, 1).'-->'; ?>
<div class="basket_props_block" id="bx_basket_div_<?=$arResult["ID"];?>" style="display: none;">
    <?if (!empty($arResult['PRODUCT_PROPERTIES_FILL'])){
        foreach ($arResult['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo){?>
            <input type="hidden" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>">
            <?if (isset($arResult['PRODUCT_PROPERTIES'][$propID]))
                unset($arResult['PRODUCT_PROPERTIES'][$propID]);
        }
    }
    $arResult["EMPTY_PROPS_JS"]="Y";
    $emptyProductProperties = empty($arResult['PRODUCT_PROPERTIES']);
    if (!$emptyProductProperties){
        $arResult["EMPTY_PROPS_JS"]="N";?>
        <div class="wrapper">
            <table>
                <?foreach ($arResult['PRODUCT_PROPERTIES'] as $propID => $propInfo){?>
                    <tr>
                        <td><? echo $arResult['PROPERTIES'][$propID]['NAME']; ?></td>
                        <td>
                            <?if('L' == $arResult['PROPERTIES'][$propID]['PROPERTY_TYPE'] && 'C' == $arResult['PROPERTIES'][$propID]['LIST_TYPE']){
                                foreach($propInfo['VALUES'] as $valueID => $value){?>
                                    <label>
                                        <input type="radio" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>><? echo $value; ?>
                                    </label>
                                <?}
                            }else{?>
                                <select name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]">
                                    <?foreach($propInfo['VALUES'] as $valueID => $value){?>
                                        <option value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>><? echo $value; ?></option>
                                    <?}?>
                                </select>
                            <?}?>
                        </td>
                    </tr>
                <?}?>
            </table>
        </div>
    <?}?>
</div>
<?
$this->setFrameMode(true);
$currencyList = '';
if (!empty($arResult['CURRENCIES'])){
    $templateLibrary[] = 'currency';
    $currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
}

$arTmpExp = $arTmpAssoc = array();

// accessories goods from property with type filter
if($arResult['PROPERTIES']['EXPANDABLES_FILTER']['~VALUE']){
    $cond = new CNextCondition();
    try{
        $arTmpExp = \Bitrix\Main\Web\Json::decode($arResult['PROPERTIES']['EXPANDABLES_FILTER']['~VALUE']);
        $arExpandablesFilter = $cond->parseCondition($arTmpExp, $arParams);
    }
    catch(\Exception $e){
        $arExpandablesFilter = array();
    }
    unset($cond);
}

// accessories goods from property with type link
if(!$arResult['PROPERTIES']['EXPANDABLES_FILTER']['~VALUE'] || !$arTmpExp || !$arTmpExp['CHILDREN']){
    $arExpValues = $arResult['PROPERTIES']['EXPANDABLES']['VALUE'];
}

// similar goods from property with type filter
if($arResult['PROPERTIES']['ASSOCIATED_FILTER']['~VALUE']){
    $cond = new CNextCondition();
    try{
        $arTmpAssoc = \Bitrix\Main\Web\Json::decode($arResult['PROPERTIES']['ASSOCIATED_FILTER']['~VALUE']);
        $arAssociatedFilter = $cond->parseCondition($arTmpAssoc, $arParams);
    }
    catch(\Exception $e){
        $arAssociatedFilter = array();
    }
    unset($cond);
}

// similar goods from property with type link
if(!$arResult['PROPERTIES']['ASSOCIATED_FILTER']['~VALUE'] || !$arTmpAssoc || !$arTmpAssoc['CHILDREN']){
    $arAssociated = $arResult['PROPERTIES']['ASSOCIATED']['VALUE'];
}

$templateData = array(
    'TEMPLATE_LIBRARY' => $templateLibrary,
    'CURRENCIES' => $currencyList,
    'EXPANDABLES' => $arExpValues,
    'EXPANDABLES_FILTER' => ($arTmpExp && $arTmpExp['CHILDREN'] ? $arExpandablesFilter : ''),
    'ASSOCIATED' => $arAssociated,
    'ASSOCIATED_FILTER' => ($arTmpAssoc && $arTmpAssoc['CHILDREN'] ? $arAssociatedFilter : ''),
    'STORES' => array(
        "USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
        "SCHEDULE" => $arParams["SCHEDULE"],
        "USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
        "MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
        "ELEMENT_ID" => $arResult["ID"],
        "STORE_PATH"  =>  $arParams["STORE_PATH"],
        "MAIN_TITLE"  =>  $arParams["MAIN_TITLE"],
        "MAX_AMOUNT"=>$arParams["MAX_AMOUNT"],
        "USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
        "SHOW_EMPTY_STORE" => $arParams['SHOW_EMPTY_STORE'],
        "SHOW_GENERAL_STORE_INFORMATION" => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
        "USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
        "USER_FIELDS" => $arParams['USER_FIELDS'],
        "FIELDS" => $arParams['FIELDS'],
        "STORES_FILTER_ORDER" => $arParams['STORES_FILTER_ORDER'],
        "STORES_FILTER" => $arParams['STORES_FILTER'],
        "STORES" => $arParams['STORES'] = $arParams['STORES'],
        "SET_ITEMS" => $arResult["SET_ITEMS"],
        "SITE_ID" => SITE_ID,
    ),
    'OFFERS_INFO' => array(
        'OFFERS' => $arResult['OFFERS'],
        'OFFER_GROUP' => $arResult['OFFER_GROUP'],
        'OFFERS_IBLOCK' => $arResult['OFFERS_IBLOCK'],
    ),
    'GIFTS_PARAMS' => array(
        'CATALOG' => $arResult['CATALOG'],
        'BUY_URL_TEMPLATE' => $arResult['~BUY_URL_TEMPLATE'],
        'ADD_URL_TEMPLATE' => $arResult['~ADD_URL_TEMPLATE'],
        'SUBSCRIBE_URL_TEMPLATE' => $arResult['~SUBSCRIBE_URL_TEMPLATE'],
        'COMPARE_URL_TEMPLATE' => $arResult['~COMPARE_URL_TEMPLATE'],
        'MODULE' => isset($arResult['MODULE']) ? $arResult['MODULE'] : 'catalog',
        'PRODUCT_PROVIDER_CLASS' => isset($arResult['PRODUCT_PROVIDER_CLASS']) ? $arResult['PRODUCT_PROVIDER_CLASS'] : 'CCatalogProductProvider',
        'QUANTITY' => isset($arResult['QUANTITY']) ? $arResult['QUANTITY'] : null,
        'IBLOCK_ID' => isset($arResult['IBLOCK_ID']) ? $arResult['IBLOCK_ID'] : null,
        'SECTION_ID' => isset($arResult['SECTION']['ID']) ? $arResult['SECTION']['ID'] : null,
        'SECTION_IBLOCK_ID' => isset($arResult['SECTION']['IBLOCK_ID']) ? $arResult['SECTION']['IBLOCK_ID'] : null,
        'SECTION_LEFT_MARGIN' => isset($arResult['SECTION']['LEFT_MARGIN']) ? $arResult['SECTION']['LEFT_MARGIN'] : null,
        'SECTION_RIGHT_MARGIN' => isset($arResult['SECTION']['RIGHT_MARGIN']) ? $arResult['SECTION']['RIGHT_MARGIN'] : null,
        'OFFER_ID' => empty($arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID']) ? $arResult['ID'] : $arResult['OFFERS'][$arResult['OFFERS_SELECTED']]['ID'],
    ),
    'VIDEO' => $arResult['VIDEO'],
    'LINK_SALE' => $arResult["PROPERTIES"]["LINK_SALE"],
    'LINK_SERVICES' => $arResult["PROPERTIES"]["SERVICES"],
);
unset($currencyList, $templateLibrary);

$defaultBlockOrder = 'tizers,complect,nabor,offers,desc,char,galery,video,reviews,gifts,ask,stores,services,docs,custom_tab,goods,exp_goods,assoc_goods,recomend_goods,podborki,blog';
$strBlockOrder = isset($arParams["DETAIL_BLOCKS_ALL_ORDER"]) ? $arParams["DETAIL_BLOCKS_ALL_ORDER"] : $defaultBlockOrder;
$arBlockOrder = explode(",", $strBlockOrder);


if($arResult["PROPERTIES"]["YM_ELEMENT_ID"] && $arResult["PROPERTIES"]["YM_ELEMENT_ID"]["VALUE"])
    $templateData["YM_ELEMENT_ID"] = $arResult["PROPERTIES"]["YM_ELEMENT_ID"]["VALUE"];

$arSkuTemplate = array();
if (!empty($arResult['SKU_PROPS'])){
    $arSkuTemplate=CNext::GetSKUPropsArray($arResult['SKU_PROPS'], $arResult["SKU_IBLOCK_ID"], "list", $arParams["OFFER_HIDE_NAME_PROPS"], 'N', $arResult, $arParams['OFFER_SHOW_PREVIEW_PICTURE_PROPS']);
}
$strMainID = $this->GetEditAreaId($arResult['ID']);

$strObName = 'ob'.preg_replace("/[^a-zA-Z0-9_]/", "x", $strMainID);

$arResult["strMainID"] = $this->GetEditAreaId($arResult['ID']);
$arItemIDs=CNext::GetItemsIDs($arResult, "Y");
$totalCount = CNext::GetTotalCount($arResult, $arParams);

$arQuantityData = CNext::GetQuantityArray($totalCount, $arItemIDs["ALL_ITEM_IDS"], "Y");
$templateData['ID_OFFER_GROUP'] = $arItemIDs['ALL_ITEM_IDS']['OFFER_GROUP'];

$arParams["BASKET_ITEMS"]=($arParams["BASKET_ITEMS"] ? $arParams["BASKET_ITEMS"] : array());
$useStores = $arParams["USE_STORE"] == "Y" && $arResult["STORES_COUNT"] && $arQuantityData["RIGHTS"]["SHOW_QUANTITY"];

$templateData['STORES']['USE_STORES'] = $useStores;

$showCustomOffer=(($arResult['OFFERS'] && $arParams["TYPE_SKU"] !="N") ? true : false);
if($showCustomOffer){
    $templateData['JS_OBJ'] = $strObName;
}
$strMeasure='';
$arAddToBasketData = array();

$templateData['STR_ID'] = $strObName;

if($arResult["OFFERS"]){
    $strMeasure=$arResult["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
    $templateData["STORES"]["OFFERS"]="Y";
    foreach($arResult["OFFERS"] as $arOffer){
        $templateData["STORES"]["OFFERS_ID"][]=$arOffer["ID"];
    }
}else{
    if (($arParams["SHOW_MEASURE"]=="Y")&&($arResult["CATALOG_MEASURE"])){
        $arMeasure = CCatalogMeasure::getList(array(), array("ID"=>$arResult["CATALOG_MEASURE"]), false, false, array())->GetNext();
        $strMeasure=$arMeasure["SYMBOL_RUS"];
    }
    $arAddToBasketData = CNext::GetAddToBasketArray($arResult, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], true, $arItemIDs["ALL_ITEM_IDS"], 'btn-lg w_icons', $arParams);
}
$arOfferProps = implode(';', $arParams['OFFERS_CART_PROPERTIES']);

// save item viewed
$arFirstPhoto = reset($arResult['MORE_PHOTO']);
$arItemPrices = $arResult['MIN_PRICE'];
if(isset($arResult['PRICE_MATRIX']) && $arResult['PRICE_MATRIX'])
{
    $rangSelected = $arResult['ITEM_QUANTITY_RANGE_SELECTED'];
    $priceSelected = $arResult['ITEM_PRICE_SELECTED'];
    if(isset($arResult['FIX_PRICE_MATRIX']) && $arResult['FIX_PRICE_MATRIX'])
    {
        $rangSelected = $arResult['FIX_PRICE_MATRIX']['RANGE_SELECT'];
        $priceSelected = $arResult['FIX_PRICE_MATRIX']['PRICE_SELECT'];
    }
    $arItemPrices = $arResult['ITEM_PRICES'][$priceSelected];
    $arItemPrices['VALUE'] = $arItemPrices['BASE_PRICE'];
    $arItemPrices['PRINT_VALUE'] = \Aspro\Functions\CAsproItem::getCurrentPrice('BASE_PRICE', $arItemPrices);
    $arItemPrices['DISCOUNT_VALUE'] = $arItemPrices['PRICE'];
    $arItemPrices['PRINT_DISCOUNT_VALUE'] = \Aspro\Functions\CAsproItem::getCurrentPrice('PRICE', $arItemPrices);
}
$arViewedData = array(
    'PRODUCT_ID' => $arResult['ID'],
    'IBLOCK_ID' => $arResult['IBLOCK_ID'],
    'NAME' => $arResult['NAME'],
    'DETAIL_PAGE_URL' => $arResult['DETAIL_PAGE_URL'],
    'PICTURE_ID' => $arResult['PREVIEW_PICTURE'] ? $arResult['PREVIEW_PICTURE']['ID'] : ($arFirstPhoto ? $arFirstPhoto['ID'] : false),
    'CATALOG_MEASURE_NAME' => $arResult['CATALOG_MEASURE_NAME'],
    'MIN_PRICE' => $arItemPrices,
    'CAN_BUY' => $arResult['CAN_BUY'] ? 'Y' : 'N',
    'IS_OFFER' => 'N',
    'WITH_OFFERS' => $arResult['OFFERS'] ? 'Y' : 'N',
);
$actualItem = $arResult["OFFERS"] ? (isset($arResult['OFFERS'][$arResult['OFFERS_SELECTED']]) ? $arResult['OFFERS'][$arResult['OFFERS_SELECTED']] : reset($arResult['OFFERS'])) : $arResult;

if($arResult["OFFERS"] && $arParams["TYPE_SKU"]=="N")
    unset($templateData['STORES']);
?>

<?
$showProps = false;
if($arResult["DISPLAY_PROPERTIES"]){
    foreach($arResult["DISPLAY_PROPERTIES"] as $arProp){
        if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE", "CML2_ARTICLE"))){
            if(!is_array($arProp["DISPLAY_VALUE"])){
                $arProp["DISPLAY_VALUE"] = array($arProp["DISPLAY_VALUE"]);
            }
            if(is_array($arProp["DISPLAY_VALUE"])){
                foreach($arProp["DISPLAY_VALUE"] as $value){
                    if(strlen($value)){
                        $showProps = true;
                        break 2;
                    }
                }
            }
        }
    }
}
if(!$showProps && $arResult['OFFERS']){
    foreach($arResult['OFFERS'] as $arOffer){
        foreach($arOffer['DISPLAY_PROPERTIES'] as $arProp){
            if(!$arResult["TMP_OFFERS_PROP"][$arProp['CODE']])
            {
                if(!is_array($arProp["DISPLAY_VALUE"]))
                    $arProp["DISPLAY_VALUE"] = array($arProp["DISPLAY_VALUE"]);

                foreach($arProp["DISPLAY_VALUE"] as $value)
                {
                    if(strlen($value))
                    {
                        $showProps = true;
                        break 3;
                    }
                }
            }
        }
    }
}

?>

<?if($arResult["OFFERS"] && $arParams["TYPE_SKU"]=="N"):?>
    <?$templateData['OFFERS_INFO']['OFFERS_MORE'] = true;?>
    <?
    $showSkUName = ((in_array('NAME', $arParams['OFFERS_FIELD_CODE'])));
    $showSkUImages = false;
    if(((in_array('PREVIEW_PICTURE', $arParams['OFFERS_FIELD_CODE']) || in_array('DETAIL_PICTURE', $arParams['OFFERS_FIELD_CODE'])))){
        foreach ($arResult["OFFERS"] as $key => $arSKU){
            if($arSKU['PREVIEW_PICTURE'] || $arSKU['DETAIL_PICTURE']){
                $showSkUImages = true;
                break;
            }
        }
    }?>
    <?if($arResult["OFFERS"] && $arParams["TYPE_SKU"] !== "TYPE_1"):?>
        <script>
            $(document).ready(function() {
                $('.catalog_detail .tabs_section .tabs_content .form.inline input[data-sid="PRODUCT_NAME"]').attr('value', $('h1').text());
            });
        </script>
    <?endif;?>
    <?if($arResult["OFFERS"] && $arParams["TYPE_SKU"] !== "TYPE_1"):?>
        <?$this->SetViewTarget('PRODUCT_OFFERS_INFO');?>
            <h4><?=($arParams["TAB_OFFERS_NAME"] ? $arParams["TAB_OFFERS_NAME"] : GetMessage("OFFER_PRICES"));?></h4>
            <div class="list-offers ajax_load">
                <div class="bx_sku_props" style="display:none;">
                    <?$arSkuKeysProp='';
                    $propSKU=$arParams["OFFERS_CART_PROPERTIES"];
                    if($propSKU){
                        $arSkuKeysProp=base64_encode(serialize(array_keys($propSKU)));
                    }?>
                    <input type="hidden" value="<?=$arSkuKeysProp;?>" />
                </div>
                <div class="table-view flexbox flexbox--row">
                    <?foreach($arResult["OFFERS"] as $key => $arSKU):?>
                        <?
                        if($arResult["PROPERTIES"]["CML2_BASE_UNIT"]["VALUE"])
                            $sMeasure = $arResult["PROPERTIES"]["CML2_BASE_UNIT"]["VALUE"];
                        else
                            $sMeasure = GetMessage("MEASURE_DEFAULT");
                        $skutotalCount = CNext::GetTotalCount($arSKU, $arParams);
                        $arskuQuantityData = CNext::GetQuantityArray($skutotalCount, array('quantity-wrapp', 'quantity-indicators'));

                        $arSKU["IBLOCK_ID"]=$arResult["IBLOCK_ID"];
                        $arSKU["IS_OFFER"]="Y";
                        $arskuAddToBasketData = CNext::GetAddToBasketArray($arSKU, $skutotalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], true, array(), 'small w_icons', $arParams);
                        $arskuAddToBasketData["HTML"] = str_replace('data-item', 'data-props="'.$arOfferProps.'" data-item', $arskuAddToBasketData["HTML"]);
                        ?>
                        <div class="table-view__item item bordered box-shadow main_item_wrapper <?=($useStores ? "table-view__item--has-stores" : "");?>">
                            <div class="table-view__item-wrapper item_info catalog-adaptive flexbox flexbox--row">
                                <?if($showSkUImages):?>
                                    <?//image-block?>
                                    <div class="item-foto">
                                        <div class="item-foto__picture">
                                            <?
                                            $srcImgPreview = $srcImgDetail = false;
                                            $imgPreviewID = ($arResult['OFFERS'][$key]['PREVIEW_PICTURE'] ? (is_array($arResult['OFFERS'][$key]['PREVIEW_PICTURE']) ? $arResult['OFFERS'][$key]['PREVIEW_PICTURE']['ID'] : $arResult['OFFERS'][$key]['PREVIEW_PICTURE']) : false);
                                            $imgDetailID = ($arResult['OFFERS'][$key]['DETAIL_PICTURE'] ? (is_array($arResult['OFFERS'][$key]['DETAIL_PICTURE']) ? $arResult['OFFERS'][$key]['DETAIL_PICTURE']['ID'] : $arResult['OFFERS'][$key]['DETAIL_PICTURE']) : false);
                                            $imgPreviewID;
                                            if($imgPreviewID || $imgDetailID){
                                                $arImgPreview = CFile::ResizeImageGet($imgPreviewID ? $imgPreviewID : $imgDetailID, array('width' => 350, 'height' => 350), BX_RESIZE_IMAGE_PROPORTIONAL, true);
                                                $srcImgPreview = $arImgPreview['src'];
                                            }
                                            if($imgDetailID){
                                                $srcImgDetail = CFile::GetPath($imgDetailID);
                                            }
                                            ?>
                                            <?if($srcImgPreview || $srcImgDetail):?>
                                                <img src="<?=$srcImgPreview?>" alt="<?=$arSKU['NAME']?>" />
                                            <?else:?>
                                                <img src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$arSKU['NAME']?>"/>
                                            <?endif;?>
                                        </div>
                                        <div class="adaptive">
                                            <div class="like_icons block">
                                                <div class="like_icons list static icons">
                                                    <?if($arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
                                                        <?if($arskuAddToBasketData['CAN_BUY']):?>
                                                            <div class="wish_item_button o_<?=$arSKU["ID"];?>">
                                                                <span title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item text to <?=$arParams["TYPE_SKU"];?>" data-item="<?=$arSKU["ID"]?>" data-iblock="<?=$arResult["IBLOCK_ID"]?>" data-offers="Y" data-props="<?=$arOfferProps?>"><i></i></span>
                                                                <span title="<?=GetMessage('CATALOG_WISH_OUT')?>" class="wish_item text in added <?=$arParams["TYPE_SKU"];?>" style="display: none;" data-item="<?=$arSKU["ID"]?>" data-iblock="<?=$arSKU["IBLOCK_ID"]?>"><i></i></span>
                                                            </div>
                                                        <?endif;?>
                                                    <?endif;?>
                                                    <?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
                                                        <div class="compare_item_button o_<?=$arSKU["ID"];?>">
                                                            <span title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item to text <?=$arParams["TYPE_SKU"];?>" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arSKU["ID"]?>" ><i></i></span>
                                                            <span title="<?=GetMessage('CATALOG_COMPARE_OUT')?>" class="compare_item in added text <?=$arParams["TYPE_SKU"];?>" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arSKU["ID"]?>"><i></i></span>
                                                        </div>
                                                    <?endif;?>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                <?endif;?>

                                <?//text-block?>
                                <div class="item-info">
                                    <div class="item-title font_sm"><?=$arSKU['NAME']?></div>
                                    <div class="quantity_block_wrapper">
                                        <?if($useStores){?>
                                            <div class="p_block">
                                        <?}?>
                                            <?=$arskuQuantityData["HTML"];?>
                                        <?if($useStores){?>
                                            </div>
                                        <?}?>
                                        <?if($arSKU['PROPERTIES']['ARTICLE']['VALUE']):?>
                                            <div class="muted article">
                                                <span class="name"><?/*=Loc::getMessage('ARTICLE_COMPACT');*/?></span><span class="value"><?=$arSKU['PROPERTIES']['ARTICLE']['VALUE'];?></span>
                                            </div>
                                        <?endif;?>
                                    </div>
                                    <?if($arResult["SKU_PROPERTIES"]):?>
                                        <div class="properties list">
                                            <div class="properties__container properties props_list">
                                                <?foreach ($arResult["SKU_PROPERTIES"] as $key => $arProp){?>
                                                    <?if(!$arProp["IS_EMPTY"] && $key != 'ARTICLE'):?>
                                                        <div class="properties__item properties__item--compact ">
                                                            <?if($arResult["TMP_OFFERS_PROP"][$arProp["CODE"]]){
                                                                echo $arResult["TMP_OFFERS_PROP"][$arProp["CODE"]]["VALUES"][$arSKU["TREE"]["PROP_".$arProp["ID"]]]["NAME"];?>
                                                                <?}else{
                                                                    if (is_array($arSKU["PROPERTIES"][$arProp["CODE"]]["VALUE"])){
                                                                        echo implode("/", $arSKU["PROPERTIES"][$arProp["CODE"]]["VALUE"]);
                                                                    }else{
                                                                        if($arSKU["PROPERTIES"][$arProp["CODE"]]["USER_TYPE"]=="directory" && isset($arSKU["PROPERTIES"][$arProp["CODE"]]["USER_TYPE_SETTINGS"]["TABLE_NAME"])){
                                                                            $rsData = \Bitrix\Highloadblock\HighloadBlockTable::getList(array('filter'=>array('=TABLE_NAME'=>$arSKU["PROPERTIES"][$arProp["CODE"]]["USER_TYPE_SETTINGS"]["TABLE_NAME"])));
                                                                            if ($arData = $rsData->fetch()){
                                                                                $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($arData);
                                                                                $entityDataClass = $entity->getDataClass();
                                                                                $arFilter = array(
                                                                                    'limit' => 1,
                                                                                    'filter' => array(
                                                                                        '=UF_XML_ID' => $arSKU["PROPERTIES"][$arProp["CODE"]]["VALUE"]
                                                                                    )
                                                                                );
                                                                                $arValue = $entityDataClass::getList($arFilter)->fetch();
                                                                                if(isset($arValue["UF_NAME"]) && $arValue["UF_NAME"]){
                                                                                    $SkuProperti = $arValue["UF_NAME"];
                                                                                }else{
                                                                                    $SkuProperti = $arSKU["PROPERTIES"][$arProp["CODE"]]["VALUE"];
                                                                                }
                                                                            }
                                                                        }else{
                                                                            $SkuProperti =  $arSKU["PROPERTIES"][$arProp["CODE"]]["VALUE"];
                                                                        }
                                                                    }
                                                                }?>
                                                            <?if($SkuProperti):?>
                                                                <div class="properties__title muted properties__item--inline char_name">
                                                                    <?if($arProp["HINT"] && $arParams["SHOW_HINTS"]=="Y"):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?>
                                                                    <span class="props_item"><?=$arProp["NAME"]?>:</span>
                                                                </div>
                                                                <div class="properties__value darken properties__item--inline char_value font_xs"><?=$SkuProperti;?></div>
                                                            <?endif;?>
                                                        </div>
                                                    <?endif;?>
                                                <?}?>
                                            </div>
                                        </div>
                                    <?endif;?>
                                </div>

                                <div class="item-actions flexbox flexbox--row">
                                    <?//prices-block?>
                                    <div class="item-price">
                                        <div class="cost prices clearfix">
                                            <?
                                            $collspan++;
                                            $arCountPricesCanAccess = 0;
                                            if(isset($arSKU['PRICE_MATRIX']) && $arSKU['PRICE_MATRIX'] && count($arSKU['PRICE_MATRIX']['ROWS']) > 1) // USE_PRICE_COUNT
                                            {?>
                                                <?=CNext::showPriceRangeTop($arSKU, $arParams, GetMessage("CATALOG_ECONOMY"));?>
                                                <?echo CNext::showPriceMatrix($arSKU, $arParams, $arSKU["CATALOG_MEASURE_NAME"]);
                                            }
                                            else
                                            {?>
                                                <?\Aspro\Functions\CAsproItem::showItemPrices($arParams, $arSKU["PRICES"], $arSKU["CATALOG_MEASURE_NAME"], $min_price_id, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y"));?>
                                            <?}?>
                                        </div>

                                        <div class="basket_props_block" id="bx_basket_div_<?=$arSKU["ID"];?>" style="display: none;">
                                            <?if (!empty($arSKU['PRODUCT_PROPERTIES_FILL'])){
                                                foreach ($arSKU['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo){?>
                                                    <input type="hidden" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>">
                                                    <?if (isset($arSKU['PRODUCT_PROPERTIES'][$propID]))
                                                        unset($arSKU['PRODUCT_PROPERTIES'][$propID]);
                                                }
                                            }
                                            $arSKU["EMPTY_PROPS_JS"]="Y";
                                            $emptyProductProperties = empty($arSKU['PRODUCT_PROPERTIES']);
                                            if (!$emptyProductProperties){
                                                $arSKU["EMPTY_PROPS_JS"]="N";?>
                                                <div class="wrapper">
                                                    <table>
                                                        <?foreach ($arSKU['PRODUCT_PROPERTIES'] as $propID => $propInfo){?>
                                                            <tr>
                                                                <td><? echo $arSKU['PROPERTIES'][$propID]['NAME']; ?></td>
                                                                <td>
                                                                    <?if('L' == $arSKU['PROPERTIES'][$propID]['PROPERTY_TYPE']	&& 'C' == $arSKU['PROPERTIES'][$propID]['LIST_TYPE']){
                                                                        foreach($propInfo['VALUES'] as $valueID => $value){?>
                                                                            <label>
                                                                                <input type="radio" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>><? echo $value; ?>
                                                                            </label>
                                                                        <?}
                                                                    }else{?>
                                                                        <select name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"><?
                                                                            foreach($propInfo['VALUES'] as $valueID => $value){?>
                                                                                <option value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>><? echo $value; ?></option>
                                                                            <?}?>
                                                                        </select>
                                                                    <?}?>
                                                                </td>
                                                            </tr>
                                                        <?}?>
                                                    </table>
                                                </div>
                                                <?
                                            }?>
                                        </div>
                                    </div>

                                    <?//buttons-block?>
                                    <div class="item-buttons item_<?=$arSKU["ID"]?> buy_block">
                                        <div class="counter_wrapp list clearfix n-mb small-block">
                                            <?if(($arskuAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] && $arskuAddToBasketData["ACTION"] == "ADD") && $arskuAddToBasketData["CAN_BUY"]):?>
                                                <div class="counter_block_inner">
                                                    <div class="counter_block" data-item="<?=$arSKU["ID"];?>">
                                                        <span class="minus">-</span>
                                                        <input type="text" class="text" name="quantity" value="<?=$arskuAddToBasketData["MIN_QUANTITY_BUY"];?>" />
                                                        <span class="plus">+</span>
                                                    </div>
                                                </div>
                                            <?endif;?>
                                            <div class="button_block <?=(($arskuAddToBasketData["ACTION"] == "ORDER" /*&& !$arResult["CAN_BUY"]*/) || !$arskuAddToBasketData["CAN_BUY"] || !$arskuAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] || ($arskuAddToBasketData["ACTION"] == "SUBSCRIBE" && $arSKU["CATALOG_SUBSCRIBE"] == "Y")  ? "wide" : "");?>">
                                                <?=$arskuAddToBasketData["HTML"]?>
                                            </div>
                                        </div>
                                        <?if($arskuAddToBasketData["ACTION"] !== "NOTHING"):?>
                                            <?if($arskuAddToBasketData["ACTION"] == "ADD" && $arskuAddToBasketData["CAN_BUY"] && $arParams["SHOW_ONE_CLICK_BUY"]!="N"):?>
                                                <div class="wrapp-one-click one_click">
                                                    <span class="btn btn-default white one_click" data-item="<?=$arSKU["ID"]?>" data-offers="Y" data-iblockID="<?=$arParams["IBLOCK_ID"]?>" data-quantity="<?=$arskuAddToBasketData["MIN_QUANTITY_BUY"];?>" data-props="<?=$arOfferProps?>" onclick="oneClickBuy('<?=$arSKU["ID"]?>', '<?=$arParams["IBLOCK_ID"]?>', this)">
                                                        <span><?=GetMessage('ONE_CLICK_BUY')?></span>
                                                    </span>
                                                </div>
                                            <?endif;?>
                                        <?endif;?>

                                        <?//delivery calculate?>
                                        <?if(
                                            $arskuAddToBasketData["ACTION"] == "ADD" &&
                                            $arskuAddToBasketData["CAN_BUY"]
                                        ):?>
                                            <?=\Aspro\Functions\CAsproNext::showCalculateDeliveryBlock($arSKU['ID'], $arParams, $arParams['TYPE_SKU'] !== 'TYPE_1');?>
                                        <?endif;?>

                                        <?
                                        if(isset($arSKU['PRICE_MATRIX']) && $arSKU['PRICE_MATRIX']) // USE_PRICE_COUNT
                                        {?>
                                            <?if($arSKU['ITEM_PRICE_MODE'] == 'Q' && count($arSKU['PRICE_MATRIX']['ROWS']) > 1):?>
                                                <?$arOnlyItemJSParams = array(
                                                    "ITEM_PRICES" => $arSKU["ITEM_PRICES"],
                                                    "ITEM_PRICE_MODE" => $arSKU["ITEM_PRICE_MODE"],
                                                    "ITEM_QUANTITY_RANGES" => $arSKU["ITEM_QUANTITY_RANGES"],
                                                    "MIN_QUANTITY_BUY" => $arskuAddToBasketData["MIN_QUANTITY_BUY"],
                                                    "SHOW_DISCOUNT_PERCENT_NUMBER" => $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"],
                                                    "ID" => $this->GetEditAreaId($arSKU["ID"]),
                                                )?>
                                                <script type="text/javascript">
                                                    var ob<? echo $this->GetEditAreaId($arSKU["ID"]); ?>el = new JCCatalogSectionOnlyElement(<? echo CUtil::PhpToJSObject($arOnlyItemJSParams, false, true); ?>);
                                                </script>
                                            <?endif;?>
                                        <?}?>
                                        <!--noindex-->
                                        <?/*if(isset($arSKU['PRICE_MATRIX']) && $arSKU['PRICE_MATRIX'] && count($arSKU['PRICE_MATRIX']['ROWS']) > 1) // USE_PRICE_COUNT
                                        {?>
                                            <?$arOnlyItemJSParams = array(
                                                "ITEM_PRICES" => $arSKU["ITEM_PRICES"],
                                                "ITEM_PRICE_MODE" => $arSKU["ITEM_PRICE_MODE"],
                                                "ITEM_QUANTITY_RANGES" => $arSKU["ITEM_QUANTITY_RANGES"],
                                                "MIN_QUANTITY_BUY" => $arskuAddToBasketData["MIN_QUANTITY_BUY"],
                                                "SHOW_DISCOUNT_PERCENT_NUMBER" => $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"],
                                                "ID" => $this->GetEditAreaId($arSKU["ID"]),
                                            )?>
                                            <script type="text/javascript">
                                                var ob<? echo $this->GetEditAreaId($arSKU["ID"]); ?>el = new JCCatalogOnlyElement(<? echo CUtil::PhpToJSObject($arOnlyItemJSParams, false, true); ?>);
                                            </script>
                                        <?}*/?>
                                    <!--/noindex-->
                                    </div>
                                </div>

                                <?//icons-block?>
                                <div class="item-icons s_2">
                                    <div class="like_icons list static icons">
                                        <?if($arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
                                            <?if($arskuAddToBasketData['CAN_BUY']):?>
                                                <div class="wish_item_button o_<?=$arSKU["ID"];?>">
                                                    <span title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item text to <?=$arParams["TYPE_SKU"];?>" data-item="<?=$arSKU["ID"]?>" data-iblock="<?=$arResult["IBLOCK_ID"]?>" data-offers="Y" data-props="<?=$arOfferProps?>"><i></i></span>
                                                    <span title="<?=GetMessage('CATALOG_WISH_OUT')?>" class="wish_item text in added <?=$arParams["TYPE_SKU"];?>" style="display: none;" data-item="<?=$arSKU["ID"]?>" data-iblock="<?=$arSKU["IBLOCK_ID"]?>"><i></i></span>
                                                </div>
                                            <?endif;?>
                                        <?endif;?>
                                        <?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
                                            <div class="compare_item_button o_<?=$arSKU["ID"];?>">
                                                <span title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item to text <?=$arParams["TYPE_SKU"];?>" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arSKU["ID"]?>" ><i></i></span>
                                                <span title="<?=GetMessage('CATALOG_COMPARE_OUT')?>" class="compare_item in added text <?=$arParams["TYPE_SKU"];?>" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arSKU["ID"]?>"><i></i></span>
                                            </div>
                                        <?endif;?>
                                    </div>
                                </div>

                                <?//stores icon?>
                                <?if($useStores):?>
                                    <div class="opener top">
                                        <?$collspan++;?>
                                        <span class="opener_icon"><i></i></span>
                                    </div>
                                <?endif;?>
                            </div>
                            <div class="offer_stores">
                                <?$APPLICATION->IncludeComponent("bitrix:catalog.store.amount", "main", array(
                                        "PER_PAGE" => "10",
                                        "USE_STORE_PHONE" => $arParams["USE_STORE_PHONE"],
                                        "SCHEDULE" => $arParams["SCHEDULE"],
                                        "USE_MIN_AMOUNT" => $arParams["USE_MIN_AMOUNT"],
                                        "MIN_AMOUNT" => $arParams["MIN_AMOUNT"],
                                        "ELEMENT_ID" => $arSKU["ID"],
                                        "STORE_PATH"  =>  $arParams["STORE_PATH"],
                                        "MAIN_TITLE"  =>  $arParams["MAIN_TITLE"],
                                        "MAX_AMOUNT"=>$arParams["MAX_AMOUNT"],
                                        "SHOW_EMPTY_STORE" => $arParams['SHOW_EMPTY_STORE'],
                                        "SHOW_GENERAL_STORE_INFORMATION" => $arParams['SHOW_GENERAL_STORE_INFORMATION'],
                                        "USE_ONLY_MAX_AMOUNT" => $arParams["USE_ONLY_MAX_AMOUNT"],
                                        "USER_FIELDS" => $arParams['USER_FIELDS'],
                                        "FIELDS" => $arParams['FIELDS'],
                                        "STORES" => $arParams['STORES'],
                                        "CACHE_TYPE" => "A",
                                        "SET_ITEMS" => $arResult["SET_ITEMS"],
                                    ),
                                    $component
                                );?>
                            </div>
                        </div>
                    <?endforeach;?>
                </div>
            </div>
        <?$this->EndViewTarget();?>
    <?endif;?>
<?endif;?>

<script type="text/javascript">
setViewedProduct(<?=$arResult['ID']?>, <?=CUtil::PhpToJSObject($arViewedData, false)?>);
</script>
<meta itemprop="name" content="<?=$name = strip_tags(!empty($arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arResult['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arResult['NAME'])?>" />
<meta itemprop="category" content="<?=$arResult['CATEGORY_PATH']?>" />
<meta itemprop="description" content="<?=(strlen(strip_tags($arResult['PREVIEW_TEXT'])) ? strip_tags($arResult['PREVIEW_TEXT']) : (strlen(strip_tags($arResult['DETAIL_TEXT'])) ? strip_tags($arResult['DETAIL_TEXT']) : $name))?>" />
<meta itemprop="sku" content="<?=$arResult['ID'];?>" />
<meta itemprop="brand" content="hypower" />
<div class="item_main_info <?=(!$showCustomOffer ? "noffer" : "");?> <?=($arParams["SHOW_UNABLE_SKU_PROPS"] != "N" ? "show_un_props" : "unshow_un_props");?>" id="<?=$arItemIDs["strMainID"];?>">
    <div class="img_wrapper swipeignore">
        <div class="stickers">
            <?$prop = ($arParams["STIKERS_PROP"] ? $arParams["STIKERS_PROP"] : "HIT");?>
            <?foreach(CNext::GetItemStickers($arResult["PROPERTIES"][$prop]) as $arSticker):?>
                <div><div class="<?=$arSticker['CLASS']?>"><?=$arSticker['VALUE']?></div></div>
            <?endforeach;?>
            <?if($arParams["SALE_STIKER"] && $arResult["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"]){?>
                <div><div class="sticker_sale_text"><?=$arResult["PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"];?></div></div>
            <?}?>
        </div>
        <div class="item_slider">
            <?if(($arParams["DISPLAY_WISH_BUTTONS"] != "N" || $arParams["DISPLAY_COMPARE"] == "Y") || (strlen($arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) || ($arResult['SHOW_OFFERS_PROPS'] && $showCustomOffer))):?>
                <div class="like_wrapper">
                    <?if($arParams["DISPLAY_WISH_BUTTONS"] != "N" || $arParams["DISPLAY_COMPARE"] == "Y"):?>
                        <div class="like_icons iblock">
                            <?if($arParams["DISPLAY_WISH_BUTTONS"] != "N"):?>
                                <?if(!$arResult["OFFERS"]):?>
                                    <div class="wish_item text" <?=($arAddToBasketData['CAN_BUY'] ? '' : 'style="display:none"');?> data-item="<?=$arResult["ID"]?>" data-iblock="<?=$arResult["IBLOCK_ID"]?>">
                                        <span class="value" title="<?=GetMessage('CT_BCE_CATALOG_IZB')?>" ><i></i></span>
                                        <span class="value added" title="<?=GetMessage('CT_BCE_CATALOG_IZB_ADDED')?>"><i></i></span>
                                    </div>
                                <?elseif($arResult["OFFERS"] && $arParams["TYPE_SKU"] === 'TYPE_1' && !empty($arResult['OFFERS_PROP'])):?>
                                    <div class="wish_item text " <?=($arAddToBasketData['CAN_BUY'] ? '' : 'style="display:none"');?> data-item="" data-iblock="<?=$arResult["IBLOCK_ID"]?>" <?=(!empty($arResult['OFFERS_PROP']) ? 'data-offers="Y"' : '');?> data-props="<?=$arOfferProps?>">
                                        <span class="value <?=$arParams["TYPE_SKU"];?>" title="<?=GetMessage('CT_BCE_CATALOG_IZB')?>"><i></i></span>
                                        <span class="value added <?=$arParams["TYPE_SKU"];?>" title="<?=GetMessage('CT_BCE_CATALOG_IZB_ADDED')?>"><i></i></span>
                                    </div>
                                <?endif;?>
                            <?endif;?>
                            <?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
                                <?if(!$arResult["OFFERS"] || ($arResult["OFFERS"] && $arParams["TYPE_SKU"] === 'TYPE_1' && !$arResult["OFFERS_PROP"])):?>
                                    <div data-item="<?=$arResult["ID"]?>" data-iblock="<?=$arResult["IBLOCK_ID"]?>" data-href="<?=$arResult["COMPARE_URL"]?>" class="compare_item text <?=($arResult["OFFERS"] ? $arParams["TYPE_SKU"] : "");?>" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['COMPARE_LINK']; ?>">
                                        <span class="value" title="<?=GetMessage('CT_BCE_CATALOG_COMPARE')?>"><i></i></span>
                                        <span class="value added" title="<?=GetMessage('CT_BCE_CATALOG_COMPARE_ADDED')?>"><i></i></span>
                                    </div>
                                <?elseif($arResult["OFFERS"] && $arParams["TYPE_SKU"] === 'TYPE_1'):?>
                                    <div data-item="" data-iblock="<?=$arResult["IBLOCK_ID"]?>" data-href="<?=$arResult["COMPARE_URL"]?>" class="compare_item text <?=$arParams["TYPE_SKU"];?>">
                                        <span class="value" title="<?=GetMessage('CT_BCE_CATALOG_COMPARE')?>"><i></i></span>
                                        <span class="value added" title="<?=GetMessage('CT_BCE_CATALOG_COMPARE_ADDED')?>"><i></i></span>
                                    </div>
                                <?endif;?>
                            <?endif;?>
                        </div>
                    <?endif;?>
                </div>
            <?endif;?>
			
            <?reset($arResult['MORE_PHOTO']);
            $arFirstPhoto = current($arResult['MORE_PHOTO']);
            $viewImgType=$arParams["DETAIL_PICTURE_MODE"];?>
            <div class="slides">
                <?if($showCustomOffer && !empty($arResult['OFFERS_PROP'])){?>
                    <div class="offers_img wof">
                        <?$alt=$arFirstPhoto["ALT"];
                        $title=$arFirstPhoto["TITLE"];?>
                        <link href="<?=($arFirstPhoto["BIG"]["src"] ? $arFirstPhoto["BIG"]["src"] : $arFirstPhoto["SRC"]);?>" itemprop="image"/>
                        <?if($arFirstPhoto["BIG"]["src"]){?>
                            <a href="<?=($viewImgType=="POPUP" ? $arFirstPhoto["BIG"]["src"] : "javascript:void(0)");?>" class="<?=($viewImgType=="POPUP" ? "popup_link" : "line_link");?>" title="<?=$title;?>">
                                <img itemprop="image" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PICT']; ?>" src="<?=$arFirstPhoto['SMALL']['src']; ?>" <?=($viewImgType=="MAGNIFIER" ? 'data-large="" data-xpreview="" data-xoriginal=""': "");?> alt="<?=$alt;?>" title="<?=$title;?>">
                                <div class="zoom"></div>
                            </a>
                        <?}else{?>
                            <a href="javascript:void(0)" class="" title="<?=$title;?>">
                                <img itemprop="image" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PICT']; ?>" src="<?=$arFirstPhoto['SRC']; ?>" alt="<?=$alt;?>" title="<?=$title;?>">
                                <div class="zoom"></div>
                            </a>
                        <?}?>
                    </div>
                <?}else{
                    if($arResult["MORE_PHOTO"]){
						$bMagnifier = ($viewImgType=="MAGNIFIER");
						$videoIframe = '';
						$showVideoFirst = $arResult['PROPERTIES']['SHOW_VIDEO_FIRST']['VALUE'] == 'Y';
						if(!empty($arResult['PROPERTIES']['PREVIEW_VIDEO_IMG']['VALUE'])){
							$imgPreVideo = '<img src="'.CFile::GetPath($arResult['PROPERTIES']['OBLOJKA_DLYA_VIDEO']['VALUE']).'" />';
						} else {
							$imgPreVideo = '<img src="/images/video3.svg" />';
						}

						if (!empty($arResult['PROPERTIES']['VIDEO_RUTUBE']['VALUE'])) {
							$videoSrc = $arResult['PROPERTIES']['VIDEO_RUTUBE']['VALUE'];
							$videoIframe = '<li class="video-slide'.($showVideoFirst ? ' current' : '').'">'.
												'<iframe id="video-rutube" width="560" height="340" src="'.$videoSrc.'?autoplay=0&mute=1&html5=1" frameborder="0" allow="autoplay" allowfullscreen></iframe>'.
										   '</li>';
							$videoNav = '<li class="video-slide'.($showVideoFirst ? ' current' : '').'">'.$imgPreVideo.'</li>';
						}
						
						if(!empty($arResult['PROPERTIES']['VIDEO_FILE']['VALUE'])){
								$videoIframe = '<li class="video-slide'.($showVideoFirst ? ' current' : '').'"><video width="'.$arResult['PROPERTIES']['VIDEO_FILE']['VALUE'][0]['width'].'" height="'.$arResult['PROPERTIES']['VIDEO_FILE']['VALUE'][0]['height'].'" controls muted><source src="'.$arResult['PROPERTIES']['VIDEO_FILE']['VALUE'][0]['path'].'" type="">Your browser does not support the video tag.</video></li>';
								$videoNav = '<li class="video-slide'.($showVideoFirst ? ' current' : '').'">'.$imgPreVideo.'</li>';
							}
						
						if(!empty($arResult['PROPERTIES']['VIDEO_FILE']['VALUE'])){
							
							$videoNav = '<li class="video-slide'.($showVideoFirst ? ' current' : '').'">'.$imgPreVideo.'</li>';
						}
					?>
					<ul>
						<?if($showVideoFirst && $videoIframe):?>
							<?=$videoIframe?>
						<?endif;?>
						<? /* if(!empty($arResult['PROPERTIES']['VIDEO_FILE']['VALUE'])){ ?>
						<li class="video-slide current">
							<video width="<?=$arResult['PROPERTIES']['VIDEO_FILE']['VALUE'][0]['width']?>" height="<?=$arResult['PROPERTIES']['VIDEO_FILE']['VALUE'][0]['height']?>" controls muted>
							  <source src="<?=$arResult['PROPERTIES']['VIDEO_FILE']['VALUE'][0]['path']?>" type="">
							  Your browser does not support the video tag.
							</video>
						</li>
						<? } */ ?>

						<?foreach($arResult["MORE_PHOTO"] as $i => $arImage){
							if($i && $bMagnifier) continue;
							$isEmpty = ($arImage["SMALL"]["src"] ? false : true);
							$alt = $arImage["ALT"];
							$title = $arImage["TITLE"];
							$isFirst = (!$showVideoFirst && $i == 0);
							/* if(!empty($arResult['PROPERTIES']['VIDEO_FILE']['VALUE'])){
								$isFirst = 0;
							} */
							
						?>
							<li id="photo-<?=$i?>" <?=($isFirst ? 'class="current"' : 'style="display: none;"')?>>
								<?if($isFirst):?>
									<link href="<?=(!$isEmpty ? $arImage["BIG"]["src"] : $arImage["SRC"]);?>" itemprop="image"/>
								<?endif;?>
								<?if(!$isEmpty){?>
									<a href="<?=($viewImgType=="POPUP" ? $arImage["BIG"]["src"] : "javascript:void(0)");?>" <?=($bIsOneImage ? '' : 'data-fancybox-group="item_slider"')?> class="<?=($viewImgType=="POPUP" ? "popup_link fancy" : "line_link");?>" title="<?=$title;?>">
										<img itemprop="image" src="<?=$arImage["SMALL"]["src"]?>" <?=($viewImgType=="MAGNIFIER" ? "class='zoom_picture'" : "");?> <?=($viewImgType=="MAGNIFIER" ? 'data-xoriginal="'.$arImage["BIG"]["src"].'" data-xpreview="'.$arImage["THUMB"]["src"].'"' : "");?> alt="<?=$alt;?>" title="<?=$title;?>"/>
										<div class="zoom"></div>
									</a>
								<?}else{?>
									<img itemprop="image" src="<?=$arImage["SRC"]?>" alt="<?=$alt;?>" title="<?=$title;?>" />
								<?}?>
							</li>
						<?}?>

						<?if(!$showVideoFirst && $videoIframe):?>
							<?=$videoIframe?>
						<?endif;?>
					</ul>
					<?}
                }?>
            </div>
            <?/*thumbs*/?>
            <?if(!$showCustomOffer || empty($arResult['OFFERS_PROP'])){
                if(count($arResult["MORE_PHOTO"]) > 1):?>
                    <div class="wrapp_thumbs xzoom-thumbs">
                        <div class="thumbs flexslider " data-plugin-options='{"animation": "slide", "selector": ".slides_block > li", "directionNav": true, "itemMargin":10, "itemWidth": 54, "controlsContainer": ".thumbs_navigation", "controlNav" :false, "animationLoop": true, "slideshow": false}' style="max-width:<?=ceil(((count($arResult['MORE_PHOTO']) <= 4 ? count($arResult['MORE_PHOTO']) : 4) * 64) - 10)?>px;">
                            <ul class="slides_block" id="thumbs">
							<?if($arResult['PROPERTIES']['SHOW_VIDEO_FIRST']['VALUE'] == 'Y'):?>
								<?=$videoNav?>
							<?endif;?>
                                <?foreach($arResult["MORE_PHOTO"]as $i => $arImage):?>
								<? $isFirst = (!$showVideoFirst && $i == 0); ?>
                                    <li <?=($isFirst ? 'class="current"' : '')?> data-big_img="<?=$arImage["BIG"]["src"]?>" data-small_img="<?=$arImage["SMALL"]["src"]?>">
                                        <span><img class="xzoom-gallery" width="50" data-xpreview="<?=$arImage["THUMB"]["src"];?>" src="<?=$arImage["THUMB"]["src"]?>" alt="<?=$arImage["ALT"];?>" title="<?=$arImage["TITLE"];?>" /></span>
                                    </li>
                                <?endforeach;?>
								<?if($arResult['PROPERTIES']['SHOW_VIDEO_FIRST']['VALUE'] != 'Y'):?>
							<?=$videoNav?>
						<?endif;?>
                            </ul>
                            <span class="thumbs_navigation custom_flex"></span>
                        </div>
                    </div>
                    <script>
                        $(document).ready(function(){
                            $('.item_slider .thumbs li').first().addClass('current');
                            $('.item_slider .thumbs .slides_block').delegate('li:not(.current)', 'click', function(){
                                var slider_wrapper = $(this).parents('.item_slider'),
                                    index = $(this).index();
                                $(this).addClass('current').siblings().removeClass('current')//.parents('.item_slider').find('.slides li').fadeOut(333);
                                if(arNextOptions['THEME']['DETAIL_PICTURE_MODE'] == 'MAGNIFIER')
                                {
                                    var li = $(this).parents('.item_slider').find('.slides li');
                                    li.find('img').attr('src', $(this).data('small_img'));
                                    li.find('img').attr('xoriginal', $(this).data('big_img'));
                                }
                                else
                                {
                                    slider_wrapper.find('.slides li').removeClass('current').hide();
                                    slider_wrapper.find('.slides li:eq('+index+')').addClass('current').show();
                                }
                            });
                        })
                    </script>
                <?endif;?>
            <?}else{?>
                <div class="wrapp_thumbs">
                    <div class="sliders">
                        <div class="thumbs" style="">
                        </div>
                    </div>
                </div>
            <?}?>
        </div>
        <?/*mobile*/?>
        <?if(!$showCustomOffer || empty($arResult['OFFERS_PROP'])){?>
		<?
		
						$videoIframe = '';
						$showVideoFirst = $arResult['PROPERTIES']['SHOW_VIDEO_FIRST']['VALUE'] == 'Y';

						if (!empty($arResult['PROPERTIES']['VIDEO_RUTUBE']['VALUE'])) {
							$videoSrc = $arResult['PROPERTIES']['VIDEO_RUTUBE']['VALUE'];
							$videoIframe = '<li class="video-slide'.($showVideoFirst ? ' current' : '').'">'.
												'<iframe id="video-rutube-mobile" width="100%" height="280" src="'.$videoSrc.'?autoplay=0&mute=1&html5=1" frameborder="0" allow="autoplay" allowfullscreen></iframe>'.
										   '</li>';
							$videoNav = '<li class="video-slide'.($showVideoFirst ? ' current' : '').'"><img src="/images/video.png" /></li>';
							
							
						}
						if(!empty($arResult['PROPERTIES']['VIDEO_FILE']['VALUE'])){
								$videoIframe = '<li class="video-slide"><video width="'.$arResult['PROPERTIES']['VIDEO_FILE']['VALUE'][0]['width'].'" height="'.$arResult['PROPERTIES']['VIDEO_FILE']['VALUE'][0]['height'].'" controls muted><source src="'.$arResult['PROPERTIES']['VIDEO_FILE']['VALUE'][0]['path'].'" type="">Your browser does not support the video tag.</video></li>';
								$videoNav = '<li class="video-slide'.($showVideoFirst ? ' current' : '').'"><img src="/images/video.png" /></li>';
							}
						?>
            <div class="item_slider flex flexslider color-controls" data-plugin-options='{"animation": "slide", "directionNav": false, "controlNav": true, "animationLoop": false, "slideshow": false, "slideshowSpeed": 10000, "animationSpeed": 600}'>
                <ul class="slides">
				<?if($showVideoFirst && $videoIframe):?>
							<?=$videoIframe?>
						<?endif;?>
						<? /* if(!empty($arResult['PROPERTIES']['VIDEO_FILE']['VALUE'])){ ?>
						<li class="video-slide">
							<video width="<?=$arResult['PROPERTIES']['VIDEO_FILE']['VALUE'][0]['width']?>" height="<?=$arResult['PROPERTIES']['VIDEO_FILE']['VALUE'][0]['height']?>" controls muted>
							  <source src="<?=$arResult['PROPERTIES']['VIDEO_FILE']['VALUE'][0]['path']?>" type="">
							  Your browser does not support the video tag.
							</video>
						</li>
						<? } */ ?>
                    <?if($arResult["MORE_PHOTO"]){
                        foreach($arResult["MORE_PHOTO"] as $i => $arImage){
							$isFirst = (!$showVideoFirst && $i == 0);
							/* if(!empty($arResult['PROPERTIES']['VIDEO_FILE']['VALUE'])){
								$isFirst = 0;
							} */
							?>
                            <?$isEmpty=($arImage["SMALL"]["src"] ? false : true );?>
                            <li id="mphoto-<?=$i?>" <?=($isFirst ? 'class="current"' : 'style="display: none;"')?>>
                                <?
                                $alt=$arImage["ALT"];
                                $title=$arImage["TITLE"];
                                ?>
                                <?if(!$isEmpty){?>
                                    <a href="<?=$arImage["BIG"]["src"]?>" data-fancybox-group="item_slider_flex" class="fancy popup_link" title="<?=$title;?>" >
                                        <img src="<?=$arImage["SMALL"]["src"]?>" alt="<?=$alt;?>" title="<?=$title;?>" />
                                        <div class="zoom"></div>
                                    </a>
                                <?}else{?>
                                    <img  src="<?=$arImage["SRC"];?>" alt="<?=$alt;?>" title="<?=$title;?>" />
                                <?}?>
                            </li>
                        <?}
                    }?>
					<?if(!$showVideoFirst && $videoIframe):?>
							<?=$videoIframe?>
						<?endif;?>
                </ul>
            </div>
        <?}else{?>
            <div class="item_slider flex color-controls"></div>
        <?}?>

        <?if($showProps && $arResult["GROUPS_PROPS"]):?>
            <?$strGrupperType = $arParams["GRUPPER_PROPS"];?>
            <div class="qv-product-detail-props">
                <h4><?=($arParams["TAB_CHAR_NAME"] ? $arParams["TAB_CHAR_NAME"] : GetMessage("PROPERTIES_TAB"));?></h4>
                <?if($showProps):?>
                    <?if($strGrupperType == "GRUPPER"):?>
                        <div class="char_block">
                            <?$APPLICATION->IncludeComponent(
                                "redsign:grupper.list",
                                "hypower",
                                Array(
                                    "CACHE_TIME" => "3600000",
                                    "CACHE_TYPE" => "A",
                                    "COMPOSITE_FRAME_MODE" => "A",
                                    "COMPOSITE_FRAME_TYPE" => "AUTO",
                                    "DISPLAY_PROPERTIES" => $arResult["GROUPS_PROPS"]
                                ),
                                $component, array('HIDE_ICONS'=>'Y')
                            );?>
                            <table class="props_list" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_DIV']; ?>"></table>
                        </div>
                    <?elseif($strGrupperType == "WEBDEBUG"):?>
                        <div class="char_block">
                            <?$APPLICATION->IncludeComponent(
                                "webdebug:propsorter",
                                "linear",
                                array(
                                    "IBLOCK_TYPE" => $arResult['IBLOCK_TYPE'],
                                    "IBLOCK_ID" => $arResult['IBLOCK_ID'],
                                    "PROPERTIES" => $arResult['GROUPS_PROPS'],
                                    "EXCLUDE_PROPERTIES" => array(),
                                    "WARNING_IF_EMPTY" => "N",
                                    "WARNING_IF_EMPTY_TEXT" => "",
                                    "NOGROUP_SHOW" => "Y",
                                    "NOGROUP_NAME" => "",
                                    "MULTIPLE_SEPARATOR" => ", "
                                ),
                                $component, array('HIDE_ICONS'=>'Y')
                            );?>
                            <table class="props_list" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_DIV']; ?>"></table>
                        </div>
                    <?elseif($strGrupperType == "YENISITE_GRUPPER"):?>
                        <div class="char_block">
                            <?$APPLICATION->IncludeComponent(
                                'yenisite:ipep.props_groups',
                                '',
                                array(
                                    'DISPLAY_PROPERTIES' => $arResult['GROUPS_PROPS'],
                                    'IBLOCK_ID' => $arParams['IBLOCK_ID']
                                ),
                                $component, array('HIDE_ICONS'=>'Y')
                            )?>
                            <table class="props_list colored_char" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_DIV']; ?>"></table>
                        </div>
                    <?else:?>
                        <?if($arParams["PROPERTIES_DISPLAY_TYPE"] != "TABLE"):?>
                            <div class="props_block" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_DIV']; ?>">
                                <?foreach($arResult["PROPERTIES"] as $propCode => $arProp):?>
                                    <?if(isset($arResult["DISPLAY_PROPERTIES"][$propCode])):?>
                                        <?$arProp = $arResult["DISPLAY_PROPERTIES"][$propCode];?>
                                        <?if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE", "CML2_ARTICLE"))):?>
                                            <?if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))):?>
                                                <div class="char" itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
                                                    <div class="char_name">
                                                        <?if($arProp["HINT"] && $arParams["SHOW_HINTS"]=="Y"):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?>
                                                        <div class="props_item <?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"){?>whint<?}?>">
                                                            <span itemprop="name"><?=$arProp["NAME"]?></span>
                                                        </div>
                                                    </div>
                                                    <div class="char_value" itemprop="value">
                                                        <?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
                                                            <?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
                                                        <?else:?>
                                                            <?=$arProp["DISPLAY_VALUE"];?>
                                                        <?endif;?>
                                                    </div>
                                                </div>
                                            <?endif;?>
                                        <?endif;?>
                                    <?endif;?>
                                <?endforeach;?>
                            </div>
                        <?else:?>
                            <div class="char_block">
                                <table class="props_list">
                                    <?foreach($arResult["DISPLAY_PROPERTIES"] as $arProp):?>
                                        <?if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE", "CML2_ARTICLE"))):?>
                                            <?if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))):?>
                                                <tr itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
                                                    <td class="char_name">
                                                        <?if($arProp["HINT"] && $arParams["SHOW_HINTS"]=="Y"):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?>
                                                        <div class="props_item <?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"){?>whint<?}?>">
                                                            <span itemprop="name"><?=$arProp["NAME"]?></span>
                                                        </div>
                                                    </td>
                                                    <td class="char_value">
                                                            <span itemprop="value">
                                                                <?if(is_array($arProp["DISPLAY_VALUE"])):?>
                                                                    <?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
                                                                <?else:?>
                                                                    <?=$arProp["DISPLAY_VALUE"];?>
                                                                <?endif;?>
                                                            </span>
                                                    </td>
                                                </tr>
                                            <?endif;?>
                                        <?endif;?>
                                    <?endforeach;?>
                                </table>
                                <table class="props_list" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_DIV']; ?>"></table>
                            </div>
                        <?endif;?>
                    <?endif;?>
                <?endif;?>
            </div>
            <script>
                document.addEventListener('DOMContentLoaded', function (e) {
                    if (window.innerWidth < 600) {
                        /*document.getElementById("qv-mobile-props").appendChild(
                            document.querySelector(".qv-product-detail-props")
                        );*/
                        document.querySelector('#content .info_item').insertBefore(
                            document.querySelector(".qv-product-detail-props"),
                            document.querySelector('#content .stock_wrapper')

                        );
                        document.querySelector('#content > .wrapper_inner').insertBefore(
                            document.getElementById('pagetitle'),
                            document.querySelector('#content > .wrapper_inner > .right_block')
                        );
                    } else {
                        document.querySelector(".container .catalog_detail .item_slider").style.marginBottom =
                            (document.querySelector(".qv-product-detail-props").offsetHeight + 140).toString() + 'px';
                    }
                });
            </script>
        <?endif;?>

    </div>
    <div class="right_info">
        <div class="info_item">
            <?$isArticle=(strlen($arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"]) || ($arResult['SHOW_OFFERS_PROPS'] && $showCustomOffer));?>
            <?if($isArticle || $arResult["BRAND_ITEM"] || $arParams["SHOW_RATING"] == "Y" || strlen($arResult["PREVIEW_TEXT"])){?>
                <div class="top_info">
                    <?if($GLOBALS["_SERVER"]["REAL_FILE_PATH"] == '/catalog/index.php'):?>
                        <h1 id="pagetitle"><?=$arResult['NAME']?></h1>
                    <?endif;?>
					<?php if (isset($arResult["GROUP_BY"]["VALUES"])): ?>
                        <span class="groups__title">
                            <?=$arResult["GROUP_BY"]["TITLE"]?>
                        </span>
                        <div class="groups">
                            <?php foreach ($arResult["GROUP_BY"]["VALUES"] as $value): ?>
                                <a href="<?= $value["LINK"] ?>" class="group__item <?php if ($APPLICATION->GetCurPage(false) === $value["LINK"]) echo "active"?>">
                                    <?= $value["NAME"] ?>
                                </a>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                    <div class="rows_block">
                        <?$col=1;
                        if($isArticle && $arResult["BRAND_ITEM"] && $arParams["SHOW_RATING"] == "Y"){
                            $col=3;
                        }elseif(($isArticle && $arResult["BRAND_ITEM"]) || ($isArticle && $arParams["SHOW_RATING"] == "Y") || ($arResult["BRAND_ITEM"] && $arParams["SHOW_RATING"] == "Y")){
                            $col=2;
                        }?>
                        <?if($arParams["SHOW_RATING"] == "Y"):?>
                            <div class="item_block item_block--rating col-<?=$col;?>">
                                <?//$frame = $this->createFrame('dv_'.$arResult["ID"])->begin();?>
                                    <div class="rating">
                                        <?if( $arParams['REVIEWS_VIEW'] == 'EXTENDED' ):?>
                                            <div class="blog-info__rating--top-info EXTENDED">
                                                <div class="votes_block nstar with-text" itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating">
                                                    <meta itemprop="ratingValue" content="<?=($arResult['PROPERTIES']['EXTENDED_REVIEWS_RAITING']['VALUE'] ? $arResult['PROPERTIES']['EXTENDED_REVIEWS_RAITING']['VALUE'] : 5)?>" />
                                                    <meta itemprop="reviewCount" content="<?=(intval($arResult['PROPERTIES']['EXTENDED_REVIEWS_COUNT']['VALUE']) ? intval($arResult['PROPERTIES']['EXTENDED_REVIEWS_COUNT']['VALUE']) : 1)?>" />
                                                    <meta itemprop="bestRating" content="5" />
                                                    <meta itemprop="worstRating" content="0" />
                                                    <div class="ratings">
                                                        <?$message = $arResult['PROPERTIES']['EXTENDED_REVIEWS_COUNT']['VALUE'] ? GetMessage('VOTES_RESULT', array('#VALUE#' => $arResult['PROPERTIES']['EXTENDED_REVIEWS_RAITING']['VALUE'])) : GetMessage('VOTES_RESULT_NONE')?>
                                                        <div class="inner_rating" title="<?=$message?>">
                                                            <?for($i=1;$i<=5;$i++):?>
                                                                <div class="item-rating large <?=$i<=$arResult['PROPERTIES']['EXTENDED_REVIEWS_RAITING']['VALUE'] ? 'filled' : ''?>"></div>
                                                            <?endfor;?>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>
                                        <?else:?>
                                            <?$APPLICATION->IncludeComponent(
                                            "bitrix:iblock.vote",
                                            "element_rating",
                                            Array(
                                                "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                                                "IBLOCK_ID" => $arResult["IBLOCK_ID"],
                                                "ELEMENT_ID" =>$arResult["ID"],
                                                "MAX_VOTE" => 5,
                                                "VOTE_NAMES" => array(),
                                                "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                                "CACHE_TIME" => $arParams["CACHE_TIME"],
                                                "DISPLAY_AS_RATING" => 'vote_avg'
                                            ),
                                            $component, array("HIDE_ICONS" =>"Y")
                                            );?>
                                        <?endif;?>
                                    </div>
                                <?//$frame->end();?>
                            </div>
                        <?endif;?>
                        <?if($isArticle):?>
                            <div class="item_block item_block--article col-<?=$col;?>">
                                <div class="article iblock" itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue" <?if($arResult['SHOW_OFFERS_PROPS']){?>id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_ARTICLE_DIV'] ?>" style="display: none;"<?}?>>
                                    <span class="block_title" itemprop="name"><?=$arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["NAME"];?>:</span>
                                    <span class="value" itemprop="value"><?=$arResult["DISPLAY_PROPERTIES"]["CML2_ARTICLE"]["VALUE"]?></span>
                                </div>
                            </div>
                        <?endif;?>

                        <?if($arResult["BRAND_ITEM"]){?>
                            <div class="item_block item_block--brand col-<?=$col;?>">
                                <div class="brand">
                                    <meta itemprop="brand" content="<?=$arResult["BRAND_ITEM"]["NAME"]?>" />
                                    <?if(!$arResult["BRAND_ITEM"]["IMAGE"]):?>
                                        <b class="block_title"><?=GetMessage("BRAND");?>:</b>
                                        <a href="<?=$arResult["BRAND_ITEM"]["DETAIL_PAGE_URL"]?>"><?=$arResult["BRAND_ITEM"]["NAME"]?></a>
                                    <?else:?>
                                        <a class="brand_picture" href="<?=$arResult["BRAND_ITEM"]["DETAIL_PAGE_URL"]?>">
                                            <img  src="<?=$arResult["BRAND_ITEM"]["IMAGE"]["src"]?>" alt="<?=$arResult["BRAND_ITEM"]["IMAGE"]["ALT"]?>" title="<?=$arResult["BRAND_ITEM"]["IMAGE"]["TITLE"]?>" />
                                        </a>
                                    <?endif;?>
                                </div>
                            </div>
                        <?}?>
                    </div>
                    <?if(strlen($arResult["PREVIEW_TEXT"])):?>
                        <div class="preview_text dotdot" style="display:none;"><?=$arResult["PREVIEW_TEXT"]?></div>
                        <?if(strlen($arResult["DETAIL_TEXT"])):?>
                            <div class="more_block icons_fa color_link"><span><?=\Bitrix\Main\Config\Option::get('aspro.next', "EXPRESSION_READ_MORE_OFFERS_DEFAULT", GetMessage("MORE_TEXT_BOTTOM"));?></span></div>
                        <?endif;?>
                    <?endif;?>
                </div>
            <?}?>
            <div class="middle_info main_item_wrapper">
                <? if($arResult['PROPERTIES']['UF_DOP_PERCENT']['VALUE']) {
                    $GLOBALS['customItemPercent'] = $arResult['PROPERTIES']['UF_DOP_PERCENT']['VALUE'];
                } else {$GLOBALS['customItemPercent'] = $GLOBALS['OPTIONS']['UF_ACTION_VALUE']['VALUE'];} ?>
            <?$frame = $this->createFrame()->begin();?>
                <div class="prices_block">
                    <div class="cost prices clearfix">
                        <?$arUserGroups = $USER->GetUserGroupArray();?>
                        <?$arCurPriceType = current($arResult['PRICE_MATRIX']['COLS']);
                        $arCurPrice = current($arResult['PRICE_MATRIX']['MATRIX'][$arCurPriceType['ID']]);
                        $min_price_id = $arCurPriceType['ID'];?>
                        <?if( count( $arResult["OFFERS"] ) > 0 ){?>
                            <div class="with_matrix" style="display:none;">
                                <div class="price price_value_block"><span class="values_wrapper"></span></div>
                                <?if($arParams["SHOW_OLD_PRICE"]=="Y"):?>
                                    <div class="price discount"></div>
                                <?endif;?>
                                <?if($arParams["SHOW_DISCOUNT_PERCENT"]=="Y"){?>
                                    <div class="sale_block matrix" style="display:none;">
                                        <div class="sale_wrapper">
                                            <?if($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] != "Y"):?>
                                                <span class="title"><?=GetMessage("CATALOG_ECONOMY");?></span>
                                                <div class="text"><span class="values_wrapper"></span></div>
                                            <?else:?>
                                                <div class="text">
                                                    <span class="title"><?=GetMessage("CATALOG_ECONOMY");?></span>
                                                    <span class="values_wrapper"></span>
                                                </div>
                                            <?endif;?>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                <?}?>
                            </div>
                            <span style="display: none" id="customDiscountPercent"><?=$GLOBALS['customItemPercent']?></span>
                            <?\Aspro\Functions\CAsproSku::showItemPrices($arParams, $arResult, $item_id, $min_price_id, $arItemIDs, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y"));?>
                        <?}else{?>
                            <?
                            $item_id = $arResult["ID"];
                            if(isset($arResult['PRICE_MATRIX']) && $arResult['PRICE_MATRIX']) // USE_PRICE_COUNT
                            {?>
                                <?if($arResult['ITEM_PRICE_MODE'] == 'Q' && count($arResult['PRICE_MATRIX']['ROWS']) > 1):?>
                                    <?=CNext::showPriceRangeTop($arResult, $arParams, GetMessage("CATALOG_ECONOMY"));?>
                                <?endif;?>

                                 <?foreach ($arResult['PRICE_MATRIX']['MATRIX'] as $pKey => $customPrice):?>
                                        <?
                                        $customPrice['ZERO-INF']['PRICE'] = calcCustomDiscount($customPrice['ZERO-INF']['PRICE'], $GLOBALS['customItemPercent']);
                                        $customPrice['ZERO-INF']['PRINT_PRICE'] = $customPrice['ZERO-INF']['PRICE'].' руб.';
                                        $arResult['PRICE_MATRIX']['MATRIX'][$pKey]['ZERO-INF'] = $customPrice['ZERO-INF'];
                                        ?>
                                 <?endforeach;?>

                                <?=CNext::showPriceMatrix($arResult, $arParams, $strMeasure, $arAddToBasketData);?>
                            <?
                            }
                            else
                            {?>
                                <?\Aspro\Functions\CAsproItem::showItemPrices($arParams, $arResult["PRICES"], $strMeasure, $min_price_id, ($arParams["SHOW_DISCOUNT_PERCENT_NUMBER"] == "Y" ? "N" : "Y"));?>
                            <?}?>
                        <?}?>
                        <?$arDiscounts = CCatalogDiscount::GetDiscountByProduct($item_id, $arUserGroups, "N", $min_price_id, SITE_ID);
                        $arDiscount=array();
                        if($arDiscounts)
                            $arDiscount=current($arDiscounts);?>
                        <?if( !$arResult["OFFERS"] && $arResult['PRICE_MATRIX']['COLS'] ){?>
                            <div class="" itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                                <meta itemprop="price" content="<?=($arResult['MIN_PRICE']['DISCOUNT_VALUE'] ? $arResult['MIN_PRICE']['DISCOUNT_VALUE'] : $arResult['MIN_PRICE']['VALUE'])?>" />
                                <meta itemprop="priceCurrency" content="<?=$arResult['MIN_PRICE']['CURRENCY']?>" />
                                <link itemprop="availability" href="http://schema.org/<?=($arResult['PRICE_MATRIX']['AVAILABLE'] == 'Y' ? 'InStock' : 'OutOfStock')?>" />
                                <?
                                if($arDiscount["ACTIVE_TO"]){?>
                                    <meta itemprop="priceValidUntil" content="<?=date("Y-m-d", MakeTimeStamp($arDiscount["ACTIVE_TO"]))?>" />
                                <?}?>
                                <link itemprop="url" href="<?=$arResult["DETAIL_PAGE_URL"]?>" />
                            </div>
                        <?}?>
                    </div>
                    <?if(isset($arResult['PROPERTIES']["NDS_INFO"]) && !empty($arResult['PROPERTIES']["NDS_INFO"]["~VALUE"]["TEXT"])):?>
                        <span><?=$arResult['PROPERTIES']["NDS_INFO"]["~VALUE"]["TEXT"]?></span>
                    <?endif?>

                    <?if($arParams["SHOW_DISCOUNT_TIME"]=="Y"){?>
                        <?$arUserGroups = $USER->GetUserGroupArray();?>
                        <?if($arParams['SHOW_DISCOUNT_TIME_EACH_SKU'] != 'Y' || ($arParams['SHOW_DISCOUNT_TIME_EACH_SKU'] == 'Y' && (!$arResult['OFFERS'] || ($arResult['OFFERS'] && $arParams['TYPE_SKU'] != 'TYPE_1')))):?>
                            <?if($arDiscount["ACTIVE_TO"]){?>
                                <div class="view_sale_block <?=($arQuantityData["HTML"] ? '' : 'wq');?>">
                                    <div class="count_d_block">
                                        <span class="active_to hidden"><?=$arDiscount["ACTIVE_TO"];?></span>
                                        <div class="title"><?=GetMessage("UNTIL_AKC");?></div>
                                        <span class="countdown values"><span class="item"></span><span class="item"></span><span class="item"></span><span class="item"></span></span>
                                    </div>
                                    <?if($arQuantityData["HTML"]):?>
                                        <div class="quantity_block">
                                            <div class="title"><?=GetMessage("TITLE_QUANTITY_BLOCK");?></div>
                                            <div class="values">
                                                <span class="item">
                                                    <span class="value" <?=((count( $arResult["OFFERS"] ) > 0 && $arParams["TYPE_SKU"] == 'TYPE_1' && $arResult["OFFERS_PROP"]) ? 'style="opacity:0;"' : '')?>><?=$totalCount;?></span>
                                                    <span class="text"><?=GetMessage("TITLE_QUANTITY");?></span>
                                                </span>
                                            </div>
                                        </div>
                                    <?endif;?>
                                </div>
                            <?}?>
                        <?else:?>
                            <?if($arResult['JS_OFFERS'])
                            {

                                foreach($arResult['JS_OFFERS'] as $keyOffer => $arTmpOffer2)
                                {
                                    $active_to = '';
                                    $arDiscounts = CCatalogDiscount::GetDiscountByProduct( $arTmpOffer2['ID'], $arUserGroups, "N", array(), SITE_ID );
                                    if($arDiscounts)
                                    {
                                        foreach($arDiscounts as $arDiscountOffer)
                                        {
                                            if($arDiscountOffer['ACTIVE_TO'])
                                            {
                                                $active_to = $arDiscountOffer['ACTIVE_TO'];
                                                break;
                                            }
                                        }
                                    }
                                    $arResult['JS_OFFERS'][$keyOffer]['DISCOUNT_ACTIVE'] = $active_to;
                                }
                            }?>
                            <div class="view_sale_block" style="display:none;">
                                <div class="count_d_block">
                                        <span class="active_to_<?=$arResult["ID"]?> hidden"><?=$arDiscount["ACTIVE_TO"];?></span>
                                        <div class="title"><?=GetMessage("UNTIL_AKC");?></div>
                                        <span class="countdown countdown_<?=$arResult["ID"]?> values"></span>
                                </div>
                                <?if($arQuantityData["HTML"]):?>
                                    <div class="quantity_block">
                                        <div class="title"><?=GetMessage("TITLE_QUANTITY_BLOCK");?></div>
                                        <div class="values">
                                            <span class="item">
                                                <span class="value"><?=$totalCount;?></span>
                                                <span class="text"><?=GetMessage("TITLE_QUANTITY");?></span>
                                            </span>
                                        </div>
                                    </div>
                                <?endif;?>
                            </div>
                        <?endif;?>
                    <?}?>
                    <div class="quantity_block_wrapper">
                        <?if($useStores){?>
                            <div class="p_block">
                        <?}?>
                            <?=$arQuantityData["HTML"];?>
                        <?if($useStores){?>
                            </div>
                        <?}?>
                        <?if($arParams["SHOW_CHEAPER_FORM"] == "Y"):?>
                            <div class="cheaper_form">
                                <span class="animate-load" data-event="jqm" data-param-form_id="CHEAPER" data-name="cheaper" data-autoload-product_name="<?=CNext::formatJsName($arResult["NAME"]);?>" data-autoload-product_id="<?=$arResult["ID"];?>"><?=($arParams["CHEAPER_FORM_NAME"] ? $arParams["CHEAPER_FORM_NAME"] : GetMessage("CHEAPER"));?></span>
                            </div>
                        <?endif;?>
                    </div>
                    <?
                    $optPriceAvailableProp = $arResult["PROPERTIES"]["OPT_PRICE_AVAILABLE"]["VALUE_XML_ID"];
                    $optQuantity = $arResult["PROPERTIES"]["KOLICHESTVO_TOVARA_DLYA_OPTOVOY_TSENY"]["VALUE"];
                    if ($optPriceAvailableProp == "Y" && $optQuantity) {?>
                        <div class="opt-price-block">
                            <div>Оптовая цена при покупке от <?=$arResult["PROPERTIES"]["KOLICHESTVO_TOVARA_DLYA_OPTOVOY_TSENY"]["VALUE"]?> <?=$arResult["ITEM_MEASURE"]["TITLE"]?></div>
                            <div class="cost prices clearfix opt-prices" data-price="<?=$arResult["OPT_PRICE"]["PRICE"]?>" data-quantity="<?=$arResult["PROPERTIES"]["KOLICHESTVO_TOVARA_DLYA_OPTOVOY_TSENY"]["VALUE"]?>">
                                <div class="price">
                                    <span class="values_wrapper">
                                        <span class="price_value"><?=$arResult["OPT_PRICE"]["PRICE"]?></span>
                                        <span class="price_currency">руб.</span></span><span class="price_measure">/<?=$arResult["ITEM_MEASURE"]["TITLE"]?></span>
                                </div>
                            </div>
                        </div>
                    <?}?>
                </div>
                <div class="buy_block">
                    <?if($arResult["OFFERS"] && $showCustomOffer){?>
                        <div class="sku_props">
                            <?if (!empty($arResult['OFFERS_PROP'])){?>
                                <div class="bx_catalog_item_scu wrapper_sku" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['PROP_DIV']; ?>">
                                    <?foreach ($arSkuTemplate as $code => $strTemplate){
                                        if (!isset($arResult['OFFERS_PROP'][$code]))
                                            continue;
                                        echo str_replace('#ITEM#_prop_', $arItemIDs["ALL_ITEM_IDS"]['PROP'], $strTemplate);
                                    }?>
                                </div>
                            <?}?>
                            <?$arItemJSParams=CNext::GetSKUJSParams($arResult, $arParams, $arResult, "Y");?>
                            <script type="text/javascript">
                                var <? echo $arItemIDs["strObName"]; ?> = new JCCatalogElement(<? echo CUtil::PhpToJSObject($arItemJSParams, false, true); ?>);
                            </script>
                        </div>
                    <?}?>
                    <?if(!$arResult["OFFERS"]):?>
                        <script>
                            $(document).ready(function() {
                                $('.catalog_detail input[data-sid="PRODUCT_NAME"]').attr('value', $('h1').text());
                            });
                        </script>
                        <div class="counter_wrapp">
                            <?if(($arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] && $arAddToBasketData["ACTION"] == "ADD") && $arAddToBasketData["CAN_BUY"]):?>
                                <div class="counter_block big_basket" data-offers="<?=($arResult["OFFERS"] ? "Y" : "N");?>" data-item="<?=$arResult["ID"];?>" <?=(($arResult["OFFERS"] && $arParams["TYPE_SKU"]=="N") ? "style='display: none;'" : "");?>>
                                    <span class="minus" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_DOWN']; ?>">-</span>
                                    <input type="text" class="text" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY']; ?>" name="<? echo $arParams["PRODUCT_QUANTITY_VARIABLE"]; ?>" value="<?=$arAddToBasketData["MIN_QUANTITY_BUY"]?>" />
                                    <span class="plus" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['QUANTITY_UP']; ?>" <?=($arAddToBasketData["MAX_QUANTITY_BUY"] ? "data-max='".$arAddToBasketData["MAX_QUANTITY_BUY"]."'" : "")?>>+</span>
                                </div>
                            <?endif;?>
                            <div id="<? echo $arItemIDs["ALL_ITEM_IDS"]['BASKET_ACTIONS']; ?>" class="button_block <?=(($arAddToBasketData["ACTION"] == "ORDER" /*&& !$arResult["CAN_BUY"]*/) || !$arAddToBasketData["CAN_BUY"] || !$arAddToBasketData["OPTIONS"]["USE_PRODUCT_QUANTITY_DETAIL"] || ($arAddToBasketData["ACTION"] == "SUBSCRIBE" && $arResult["CATALOG_SUBSCRIBE"] == "Y")  ? "wide" : "");?>">
                                <!--noindex-->
                                    <?=$arAddToBasketData["HTML"]?>
                                <!--/noindex-->
                            </div>
                        </div>
                        <?if(isset($arResult['PRICE_MATRIX']) && $arResult['PRICE_MATRIX']) // USE_PRICE_COUNT
                        {?>
                            <?if($arResult['ITEM_PRICE_MODE'] == 'Q' && count($arResult['PRICE_MATRIX']['ROWS']) > 1):?>
                                <?$arOnlyItemJSParams = array(
                                    "ITEM_PRICES" => $arResult["ITEM_PRICES"],
                                    "ITEM_PRICE_MODE" => $arResult["ITEM_PRICE_MODE"],
                                    "ITEM_QUANTITY_RANGES" => $arResult["ITEM_QUANTITY_RANGES"],
                                    "MIN_QUANTITY_BUY" => $arAddToBasketData["MIN_QUANTITY_BUY"],
                                    "SHOW_DISCOUNT_PERCENT_NUMBER" => $arParams["SHOW_DISCOUNT_PERCENT_NUMBER"],
                                    "ID" => $arItemIDs["strMainID"],
                                )?>
                                <script type="text/javascript">
                                    var <? echo $arItemIDs["strObName"]; ?>el = new JCCatalogOnlyElement(<? echo CUtil::PhpToJSObject($arOnlyItemJSParams, false, true); ?>);
                                </script>
                            <?endif;?>
                        <?}?>
                        <?if($arAddToBasketData["ACTION"] !== "NOTHING"):?>
                            <?if($arAddToBasketData["ACTION"] == "ADD" && $arAddToBasketData["CAN_BUY"] && $arParams["SHOW_ONE_CLICK_BUY"]!="N"):?>
                                <div class="wrapp_one_click">
                                    <span class="btn btn-default white btn-lg type_block transition_bg one_click" data-item="<?=$arResult["ID"]?>" data-iblockID="<?=$arParams["IBLOCK_ID"]?>" data-quantity="<?=$arAddToBasketData["MIN_QUANTITY_BUY"];?>" onclick="oneClickBuy('<?=$arResult["ID"]?>', '<?=$arParams["IBLOCK_ID"]?>', this)">
                                        <span><?=GetMessage('ONE_CLICK_BUY')?></span>
                                    </span>
                                </div>
                            <?endif;?>
                        <?endif;?>
                    <?elseif($arResult["OFFERS"] && $arParams['TYPE_SKU'] == 'TYPE_1'):?>
                        <div class="offer_buy_block buys_wrapp" style="display:none;">
                            <div class="counter_wrapp"></div>
                        </div>
                    <?elseif($arResult["OFFERS"] && $arParams['TYPE_SKU'] != 'TYPE_1'):?>
                        <span class="btn btn-default btn-lg slide_offer transition_bg type_block"><i></i><span><?=\Bitrix\Main\Config\Option::get("aspro.next", "EXPRESSION_READ_MORE_OFFERS_DEFAULT", GetMessage("MORE_TEXT_BOTTOM"));?></span></span>
                    <?endif;?>
                </div>

                <?//delivery calculate?>
                <?if(
                    (
                        !$arResult["OFFERS"] &&
                        $arAddToBasketData["ACTION"] == "ADD" &&
                        $arAddToBasketData["CAN_BUY"]
                    ) ||
                    (
                        $arResult["OFFERS"] &&
                        $arParams['TYPE_SKU'] === 'TYPE_1'
                    )
                ):?>
                    <?
                    ?>
                    <?=\Aspro\Functions\CAsproNext::showCalculateDeliveryBlock($arResult['ID'], $arParams);?>
                <?endif;?>
            <?$frame->end();?>
            </div>
            <div class="stock_wrapper" style="display:none;"></div>
            <div class="element_detail_text wrap_md">
                <div class="price_txt">
                    <?if($arParams["USE_SHARE"] !== 'N'):?>
                        <div class="sharing">
                            <div class="">
                                <?$APPLICATION->IncludeFile(SITE_DIR."include/share_buttons.php", Array(), Array("MODE" => "html", "NAME" => GetMessage('CT_BCE_CATALOG_SOC_BUTTON')));?>
                            </div>
                        </div>
                    <?endif;?>
                    <div class="text">
                        <?$APPLICATION->IncludeFile(SITE_DIR."include/element_detail_text.php", Array(), Array("MODE" => "html",  "NAME" => GetMessage('CT_BCE_CATALOG_DOP_DESCR')));?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?$bPriceCount = ($arParams['USE_PRICE_COUNT'] == 'Y');?>
    <?if($arResult['OFFERS']):?>
        <span itemprop="offers" itemscope itemtype="http://schema.org/AggregateOffer" style="display:none;">
            <meta itemprop="offerCount" content="<?=count($arResult['OFFERS'])?>" />
            <meta itemprop="lowPrice" content="<?=($arResult['MIN_PRICE']['DISCOUNT_VALUE'] ? $arResult['MIN_PRICE']['DISCOUNT_VALUE'] : $arResult['MIN_PRICE']['VALUE'] )?>" />
            <meta itemprop="highPrice" content="<?=($arResult['MAX_PRICE']['DISCOUNT_VALUE'] ? $arResult['MAX_PRICE']['DISCOUNT_VALUE'] : $arResult['MAX_PRICE']['VALUE'] )?>" />
            <meta itemprop="priceCurrency" content="<?=$arResult['MIN_PRICE']['CURRENCY']?>" />
            <?foreach($arResult['OFFERS'] as $arOffer):?>
                <?$currentOffersList = array();?>
                <?foreach($arOffer['TREE'] as $propName => $skuId):?>
                    <?$propId = (int)substr($propName, 5);?>
                    <?foreach($arResult['SKU_PROPS'] as $prop):?>
                        <?if($prop['ID'] == $propId):?>
                            <?foreach($prop['VALUES'] as $propId => $propValue):?>
                                <?if($propId == $skuId):?>
                                    <?$currentOffersList[] = $propValue['NAME'];?>
                                    <?break;?>
                                <?endif;?>
                            <?endforeach;?>
                        <?endif;?>
                    <?endforeach;?>
                <?endforeach;?>
                <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                    <meta itemprop="sku" content="<?=implode('/', $currentOffersList)?>" />
                    <link itemprop="url" href="<?=$arOffer['DETAIL_PAGE_URL']?>" />
                    <meta itemprop="price" content="<?=($arOffer['MIN_PRICE']['DISCOUNT_VALUE']) ? $arOffer['MIN_PRICE']['DISCOUNT_VALUE'] : $arOffer['MIN_PRICE']['VALUE']?>" />
                    <meta itemprop="priceCurrency" content="<?=$arOffer['MIN_PRICE']['CURRENCY']?>" />
                    <link itemprop="availability" href="http://schema.org/<?=($arOffer['CAN_BUY'] ? 'InStock' : 'OutOfStock')?>" />
                    <?
                    if($arDiscount["ACTIVE_TO"]){?>
                        <meta itemprop="priceValidUntil" content="<?=date("Y-m-d", MakeTimeStamp($arDiscount["ACTIVE_TO"]))?>" />
                    <?}?>
                </span>
            <?endforeach;?>
        </span>
        <?unset($arOffer, $currentOffersList);?>
    <?else:?>
        <?if(!$bPriceCount):?>
            <span itemprop="offers" itemscope itemtype="http://schema.org/Offer">
                <meta itemprop="price" content="<?=($arResult['MIN_PRICE']['DISCOUNT_VALUE'] ? $arResult['MIN_PRICE']['DISCOUNT_VALUE'] : $arResult['MIN_PRICE']['VALUE'])?>" />
                <meta itemprop="priceCurrency" content="<?=$arResult['MIN_PRICE']['CURRENCY']?>" />
                <link itemprop="availability" href="http://schema.org/<?=($arResult['MIN_PRICE']['CAN_BUY'] ? 'InStock' : 'OutOfStock')?>" />
                <?
                if($arDiscount["ACTIVE_TO"]){?>
                    <meta itemprop="priceValidUntil" content="<?=date("Y-m-d", MakeTimeStamp($arDiscount["ACTIVE_TO"]))?>" />
                <?}?>
                <link itemprop="url" href="<?=$arResult["DETAIL_PAGE_URL"]?>" />
            </span>
        <?endif;?>
    <?endif;?>
    <div class="clearleft"></div>
    <?//tizers block start?>
    <?ob_start()?>
    <?if($arResult["TIZERS_ITEMS"]){?>
        <div class="tizers_block_detail tizers_block <?=($arBlockOrder[0] == 'tizers' ? '' : 'drag_block_detail')?>">
            <div class="row">
                <?$count_t_items=count($arResult["TIZERS_ITEMS"]);?>
                <?foreach($arResult["TIZERS_ITEMS"] as $arItem){?>
                    <div class="col-md-3 col-sm-3 col-xs-6">
                        <div class="inner_wrapper item">
                            <?if($arItem["UF_FILE"]){?>
                                <div class="img">
                                    <?if($arItem["UF_LINK"]){?>
                                        <a href="<?=$arItem["UF_LINK"];?>" <?=(strpos($arItem["UF_LINK"], "http") !== false ? "target='_blank' rel='nofollow'" : '')?>>
                                    <?}?>
                                    <img src="<?=$arItem["PREVIEW_PICTURE"]["src"];?>" alt="<?=$arItem["UF_NAME"];?>" title="<?=$arItem["UF_NAME"];?>">
                                    <?if($arItem["UF_LINK"]){?>
                                        </a>
                                    <?}?>
                                </div>
                            <?}?>
                            <div class="title">
                                <?if($arItem["UF_LINK"]){?>
                                    <a href="<?=$arItem["UF_LINK"];?>" <?=(strpos($arItem["UF_LINK"], "http") !== false ? "target='_blank' rel='nofollow'" : '')?>>
                                <?}?>
                                <?=$arItem["UF_NAME"];?>
                                <?if($arItem["UF_LINK"]){?>
                                    </a>
                                <?}?>
                            </div>
                        </div>
                    </div>
                <?}?>
            </div>
        </div>
    <?}?>
    <?$htmlTizers=ob_get_clean();?>
    <?$this->SetViewTarget('TIZERS_BLOCK');?>
        <?=$htmlTizers;?>
    <?$this->EndViewTarget();?>
    <?if($arBlockOrder[0] == 'tizers'):?>
        <?=$htmlTizers;?>
    <?endif;?>
    <?//tizers block end?>

</div>


<?
if($arResult['CATALOG'] && $actualItem['CAN_BUY'] && $arParams['USE_PREDICTION'] === 'Y' && \Bitrix\Main\ModuleManager::isModuleInstalled('sale')){
    $APPLICATION->IncludeComponent(
        'bitrix:sale.prediction.product.detail',
        'main',
        array(
            'BUTTON_ID' => false,
            'CUSTOM_SITE_ID' => isset($arParams['CUSTOM_SITE_ID']) ? $arParams['CUSTOM_SITE_ID'] : null,
            'POTENTIAL_PRODUCT_TO_BUY' => array(
                'ID' => $arResult['ID'],
                'MODULE' => isset($arResult['MODULE']) ? $arResult['MODULE'] : 'catalog',
                'PRODUCT_PROVIDER_CLASS' => isset($arResult['PRODUCT_PROVIDER_CLASS']) ? $arResult['PRODUCT_PROVIDER_CLASS'] : 'CCatalogProductProvider',
                'QUANTITY' => isset($arResult['QUANTITY']) ? $arResult['QUANTITY'] : null,
                'IBLOCK_ID' => $arResult['IBLOCK_ID'],
                'PRIMARY_OFFER_ID' => isset($arResult['OFFERS'][0]['ID']) ? $arResult['OFFERS'][0]['ID'] : null,
                'SECTION' => array(
                    'ID' => isset($arResult['SECTION']['ID']) ? $arResult['SECTION']['ID'] : null,
                    'IBLOCK_ID' => isset($arResult['SECTION']['IBLOCK_ID']) ? $arResult['SECTION']['IBLOCK_ID'] : null,
                    'LEFT_MARGIN' => isset($arResult['SECTION']['LEFT_MARGIN']) ? $arResult['SECTION']['LEFT_MARGIN'] : null,
                    'RIGHT_MARGIN' => isset($arResult['SECTION']['RIGHT_MARGIN']) ? $arResult['SECTION']['RIGHT_MARGIN'] : null,
                ),
            )
        ),
        $component,
        array('HIDE_ICONS' => 'Y')
    );
}
?>

<?/*buffers bloks for epilog*/?>

<?//custom tab?>
<?/*$this->SetViewTarget('PRODUCT_CUSTOM_TAB_INFO');?>
    <?if($arParams["SHOW_ADDITIONAL_TAB"] == "Y"):?>
        <div class="wraps">
            <hr>
            <h4><?=($arParams["TAB_DOPS_NAME"] ? $arParams["TAB_DOPS_NAME"] : GetMessage("ADDITIONAL_TAB"));?></h4>
            <div class="additional_block" id="dops">
                <?$APPLICATION->IncludeFile(SITE_DIR."include/additional_products_description.php", array(), array("MODE" => "html", "NAME" => GetMessage('CT_BCE_CATALOG_ADDITIONAL_DESCRIPTION')));?>
            </div>
        </div>
    <?endif;?>
<?$this->EndViewTarget();*/?>


<?//detail description?>
<?$this->SetViewTarget('PRODUCT_DETAIL_TEXT_INFO');?>
    <?if($arResult["DETAIL_TEXT"] || $showProps):
        $class = 12;
        if($arResult["DETAIL_TEXT"] && !empty($arResult['PROPERTIES']['PROP_ELMS_SIMILAR']['VALUE']))
            $class = 6;?>
        <div class="row">
            <div class="lowest-price">
                <div class="col-md-6 low-price-right">
                    <?if(!empty($arResult['PROPERTIES']['PROP_ELMS_SIMILAR']['VALUE'])):?>
                <div class="hidden_print addon_type drag_block_detail separate_block">
                    <h4>Похожий товар</h4>
                    <div class="<?=($blockViewType ? '' : 'bottom_slider');?> specials tab_slider_wrapp custom_type">
                        <ul class="slider_navigation top custom_flex border">
                            <li class="tabs_slider_navigation similar_nav cur" data-code="similar"></li>
                        </ul>
                        <?if ($blockViewType):?>
                        <div class="wraps goods-block with-padding block ajax_load catalog">
                            <?else:?>
                            <ul class="tabs_content">
                                <li class="tab similar_wrapp cur" data-code="similar">
                                    <div class="flexslider loading_state shadow border custom_flex top_right" data-plugin-options='{"animation": "slide", "animationSpeed": 600, "directionNav": true, "controlNav" :false, "animationLoop": true, "slideshow": false, "controlsContainer": ".tabs_slider_navigation.similar_nav", "counts": [2,1,1,1,1]}'>
                                        <ul class="tabs_slider similar_slides slides">
                                            <?endif;?>
                                            <?
                                            $GLOBALS['arrFilterSimilar'] = array('ID' => $arResult['PROPERTIES']['PROP_ELMS_SIMILAR']['VALUE']);
                                            $GLOBALS['arrFilterSimilar']['IBLOCK_ID'] = $arParams['IBLOCK_ID'];
                                            ?>
                                            <?$APPLICATION->IncludeComponent(
                                                'bitrix:catalog.top',
                                                'main',
                                                array(
                                                    "USE_REGION" => 'N',
                                                    "STORES" => $arParams['STORES'],
                                                    "TITLE_BLOCK" => $arParams["SECTION_TOP_BLOCK_TITLE"],
                                                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                                                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                                                    "SALE_STIKER" => $arParams["SALE_STIKER"],
                                                    "STIKERS_PROP" => $arParams["STIKERS_PROP"],
                                                    "SHOW_RATING" => $arParams["SHOW_RATING"],
                                                    "DISPLAY_TOP_PAGER" => "N",
                                                    "DISPLAY_BOTTOM_PAGER" => "N",
                                                    "FILTER_NAME" => 'arrFilterSimilar',
                                                    "CUSTOM_FILTER" => '',
                                                    "ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
                                                    "ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
                                                    "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
                                                    "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
                                                    "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                                                    "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
                                                    "BASKET_URL" => $arParams["BASKET_URL"],
                                                    "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                                                    "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                                                    "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                                                    "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                                                    "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                                                    "DISPLAY_COMPARE" => ($arParams["DISPLAY_COMPARE"] ? "Y" : "N"),
                                                    "DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
                                                    "ELEMENT_COUNT" => 10,
                                                    "SHOW_MEASURE_WITH_RATIO" => $arParams["SHOW_MEASURE_WITH_RATIO"],
                                                    "SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
                                                    "LINE_ELEMENT_COUNT" => $arParams["TOP_LINE_ELEMENT_COUNT"],
                                                    "PROPERTY_CODE" => $arParams["PROPERTY_CODE"],
                                                    "PRICE_CODE" => $arParams['PRICE_CODE'],
                                                    "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                                                    "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
                                                    "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                                                    "PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
                                                    "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
                                                    "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
                                                    "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                                                    "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
                                                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                                                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                                                    "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                                                    "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
                                                    "OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
                                                    "OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
                                                    "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
                                                    "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
                                                    "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
                                                    "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
                                                    "OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
                                                    'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                                                    'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                                                    'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
                                                    'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
                                                    'VIEW_MODE' => (isset($arParams['TOP_VIEW_MODE']) ? $arParams['TOP_VIEW_MODE'] : ''),
                                                    'ROTATE_TIMER' => (isset($arParams['TOP_ROTATE_TIMER']) ? $arParams['TOP_ROTATE_TIMER'] : ''),
                                                    'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
                                                    'LABEL_PROP' => $arParams['LABEL_PROP'],
                                                    'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
                                                    'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
                                                    'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
                                                    'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
                                                    'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
                                                    'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
                                                    'SHOW_DISCOUNT_PERCENT_NUMBER' => $arParams['SHOW_DISCOUNT_PERCENT_NUMBER'],
                                                    'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
                                                    'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
                                                    'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
                                                    'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
                                                    'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
                                                    'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
                                                    'ADD_TO_BASKET_ACTION' => $basketAction,
                                                    "ADD_PICT_PROP" => ($arParams["ADD_PICT_PROP"] ? $arParams["ADD_PICT_PROP"] : 'MORE_PHOTO'),
                                                    "OFFER_ADD_PICT_PROP" => ($arParams["OFFER_ADD_PICT_PROP"] ? $arParams["OFFER_ADD_PICT_PROP"] : 'MORE_PHOTO'),
                                                    "GALLERY_ITEM_SHOW" => $GLOBALS["arTheme"]["GALLERY_ITEM_SHOW"]["VALUE"],
                                                    "MAX_GALLERY_ITEMS" => $GLOBALS["arTheme"]["GALLERY_ITEM_SHOW"]["DEPENDENT_PARAMS"]["MAX_GALLERY_ITEMS"]["VALUE"],
                                                    "ADD_DETAIL_TO_GALLERY_IN_LIST" => $GLOBALS["arTheme"]["GALLERY_ITEM_SHOW"]["DEPENDENT_PARAMS"]["ADD_DETAIL_TO_GALLERY_IN_LIST"]["VALUE"],
                                                    'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
                                                    'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
                                                    "REVIEWS_VIEW" => $arParams["REVIEWS_VIEW"] == 'EXTENDED',
													"COMPATIBLE_MODE" => "Y"
                                                ),
                                                false, array("HIDE_ICONS"=>"Y")
                                            );?>
                                            <?if ($blockViewType):?>
                                    </div>
                                    <?else:?>
                            </ul>
                        </div>
                        </li>
                        </ul>
                    <?endif;?>
                    </div>
                </div>
            <?endif;?>
                </div>
            </div>
            <?if($arResult["DETAIL_TEXT"]):?>
                <div class="col-md-12">

                    <h4><?=($arParams["TAB_DESCR_NAME"] ? $arParams["TAB_DESCR_NAME"] : GetMessage("DESCRIPTION_TAB"));?></h4>
                    <?=$arResult["DETAIL_TEXT"];?>
                     <?if(!empty($arResult['PROPERTIES']['PROP_ELMS_SIMILAR']['VALUE'])):?>
                <div class="low-price-mobile hidden_print addon_type drag_block_detail separate_block">
                    <h4>Похожий товар</h4>
                    <div class="<?=($blockViewType ? '' : 'bottom_slider');?> specials tab_slider_wrapp custom_type">
                        <ul class="slider_navigation top custom_flex border">
                            <li class="tabs_slider_navigation similar_nav cur" data-code="similar"></li>
                        </ul>
                        <?if ($blockViewType):?>
                        <div class="wraps goods-block with-padding block ajax_load catalog">
                            <?else:?>
                            <ul class="tabs_content">
                                <li class="tab similar_wrapp cur" data-code="similar">
                                    <div class="flexslider loading_state shadow border custom_flex top_right" data-plugin-options='{"animation": "slide", "animationSpeed": 600, "directionNav": true, "controlNav" :false, "animationLoop": true, "slideshow": false, "controlsContainer": ".tabs_slider_navigation.similar_nav", "counts": [2,1,1,1,1]}'>
                                        <ul class="tabs_slider similar_slides slides">
                                            <?endif;?>
                                            <?
                                            $GLOBALS['arrFilterSimilar'] = array('ID' => $arResult['PROPERTIES']['PROP_ELMS_SIMILAR']['VALUE']);
                                            $GLOBALS['arrFilterSimilar']['IBLOCK_ID'] = $arParams['IBLOCK_ID'];
                                            ?>
                                            <?$APPLICATION->IncludeComponent(
                                                'bitrix:catalog.top',
                                                'main',
                                                array(
                                                    "USE_REGION" => 'N',
                                                    "STORES" => $arParams['STORES'],
                                                    "TITLE_BLOCK" => $arParams["SECTION_TOP_BLOCK_TITLE"],
                                                    "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
                                                    "IBLOCK_ID" => $arParams["IBLOCK_ID"],
                                                    "SALE_STIKER" => $arParams["SALE_STIKER"],
                                                    "STIKERS_PROP" => $arParams["STIKERS_PROP"],
                                                    "SHOW_RATING" => $arParams["SHOW_RATING"],
                                                    "DISPLAY_TOP_PAGER" => "N",
                                                    "DISPLAY_BOTTOM_PAGER" => "N",
                                                    "FILTER_NAME" => 'arrFilterSimilar',
                                                    "CUSTOM_FILTER" => '',
                                                    "ELEMENT_SORT_FIELD" => $arParams["ELEMENT_SORT_FIELD"],
                                                    "ELEMENT_SORT_ORDER" => $arParams["ELEMENT_SORT_ORDER"],
                                                    "ELEMENT_SORT_FIELD2" => $arParams["ELEMENT_SORT_FIELD2"],
                                                    "ELEMENT_SORT_ORDER2" => $arParams["ELEMENT_SORT_ORDER2"],
                                                    "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
                                                    "DETAIL_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["element"],
                                                    "BASKET_URL" => $arParams["BASKET_URL"],
                                                    "ACTION_VARIABLE" => $arParams["ACTION_VARIABLE"],
                                                    "PRODUCT_ID_VARIABLE" => $arParams["PRODUCT_ID_VARIABLE"],
                                                    "SECTION_ID_VARIABLE" => $arParams["SECTION_ID_VARIABLE"],
                                                    "PRODUCT_QUANTITY_VARIABLE" => $arParams["PRODUCT_QUANTITY_VARIABLE"],
                                                    "PRODUCT_PROPS_VARIABLE" => $arParams["PRODUCT_PROPS_VARIABLE"],
                                                    "DISPLAY_COMPARE" => ($arParams["DISPLAY_COMPARE"] ? "Y" : "N"),
                                                    "DISPLAY_WISH_BUTTONS" => $arParams["DISPLAY_WISH_BUTTONS"],
                                                    "ELEMENT_COUNT" => 10,
                                                    "SHOW_MEASURE_WITH_RATIO" => $arParams["SHOW_MEASURE_WITH_RATIO"],
                                                    "SHOW_MEASURE" => $arParams["SHOW_MEASURE"],
                                                    "LINE_ELEMENT_COUNT" => $arParams["TOP_LINE_ELEMENT_COUNT"],
                                                    "PROPERTY_CODE" => $arParams["PROPERTY_CODE"],
                                                    "PRICE_CODE" => $arParams['PRICE_CODE'],
                                                    "USE_PRICE_COUNT" => $arParams["USE_PRICE_COUNT"],
                                                    "SHOW_PRICE_COUNT" => $arParams["SHOW_PRICE_COUNT"],
                                                    "PRICE_VAT_INCLUDE" => $arParams["PRICE_VAT_INCLUDE"],
                                                    "PRICE_VAT_SHOW_VALUE" => $arParams["PRICE_VAT_SHOW_VALUE"],
                                                    "USE_PRODUCT_QUANTITY" => $arParams['USE_PRODUCT_QUANTITY'],
                                                    "ADD_PROPERTIES_TO_BASKET" => (isset($arParams["ADD_PROPERTIES_TO_BASKET"]) ? $arParams["ADD_PROPERTIES_TO_BASKET"] : ''),
                                                    "PARTIAL_PRODUCT_PROPERTIES" => (isset($arParams["PARTIAL_PRODUCT_PROPERTIES"]) ? $arParams["PARTIAL_PRODUCT_PROPERTIES"] : ''),
                                                    "PRODUCT_PROPERTIES" => $arParams["PRODUCT_PROPERTIES"],
                                                    "CACHE_TYPE" => $arParams["CACHE_TYPE"],
                                                    "CACHE_TIME" => $arParams["CACHE_TIME"],
                                                    "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
                                                    "CACHE_FILTER" => $arParams["CACHE_FILTER"],
                                                    "OFFERS_CART_PROPERTIES" => $arParams["OFFERS_CART_PROPERTIES"],
                                                    "OFFERS_FIELD_CODE" => $arParams["LIST_OFFERS_FIELD_CODE"],
                                                    "OFFERS_PROPERTY_CODE" => $arParams["OFFERS_PROPERTY_CODE"],
                                                    "OFFERS_SORT_FIELD" => $arParams["OFFERS_SORT_FIELD"],
                                                    "OFFERS_SORT_ORDER" => $arParams["OFFERS_SORT_ORDER"],
                                                    "OFFERS_SORT_FIELD2" => $arParams["OFFERS_SORT_FIELD2"],
                                                    "OFFERS_SORT_ORDER2" => $arParams["OFFERS_SORT_ORDER2"],
                                                    "OFFERS_LIMIT" => $arParams["LIST_OFFERS_LIMIT"],
                                                    'CONVERT_CURRENCY' => $arParams['CONVERT_CURRENCY'],
                                                    'CURRENCY_ID' => $arParams['CURRENCY_ID'],
                                                    'HIDE_NOT_AVAILABLE' => $arParams['HIDE_NOT_AVAILABLE'],
                                                    'HIDE_NOT_AVAILABLE_OFFERS' => $arParams["HIDE_NOT_AVAILABLE_OFFERS"],
                                                    'VIEW_MODE' => (isset($arParams['TOP_VIEW_MODE']) ? $arParams['TOP_VIEW_MODE'] : ''),
                                                    'ROTATE_TIMER' => (isset($arParams['TOP_ROTATE_TIMER']) ? $arParams['TOP_ROTATE_TIMER'] : ''),
                                                    'TEMPLATE_THEME' => (isset($arParams['TEMPLATE_THEME']) ? $arParams['TEMPLATE_THEME'] : ''),
                                                    'LABEL_PROP' => $arParams['LABEL_PROP'],
                                                    'ADD_PICT_PROP' => $arParams['ADD_PICT_PROP'],
                                                    'PRODUCT_DISPLAY_MODE' => $arParams['PRODUCT_DISPLAY_MODE'],
                                                    'OFFER_ADD_PICT_PROP' => $arParams['OFFER_ADD_PICT_PROP'],
                                                    'OFFER_TREE_PROPS' => $arParams['OFFER_TREE_PROPS'],
                                                    'PRODUCT_SUBSCRIPTION' => $arParams['PRODUCT_SUBSCRIPTION'],
                                                    'SHOW_DISCOUNT_PERCENT' => $arParams['SHOW_DISCOUNT_PERCENT'],
                                                    'SHOW_DISCOUNT_PERCENT_NUMBER' => $arParams['SHOW_DISCOUNT_PERCENT_NUMBER'],
                                                    'SHOW_OLD_PRICE' => $arParams['SHOW_OLD_PRICE'],
                                                    'MESS_BTN_BUY' => $arParams['MESS_BTN_BUY'],
                                                    'MESS_BTN_ADD_TO_BASKET' => $arParams['MESS_BTN_ADD_TO_BASKET'],
                                                    'MESS_BTN_SUBSCRIBE' => $arParams['MESS_BTN_SUBSCRIBE'],
                                                    'MESS_BTN_DETAIL' => $arParams['MESS_BTN_DETAIL'],
                                                    'MESS_NOT_AVAILABLE' => $arParams['MESS_NOT_AVAILABLE'],
                                                    'ADD_TO_BASKET_ACTION' => $basketAction,
                                                    "ADD_PICT_PROP" => ($arParams["ADD_PICT_PROP"] ? $arParams["ADD_PICT_PROP"] : 'MORE_PHOTO'),
                                                    "OFFER_ADD_PICT_PROP" => ($arParams["OFFER_ADD_PICT_PROP"] ? $arParams["OFFER_ADD_PICT_PROP"] : 'MORE_PHOTO'),
                                                    "GALLERY_ITEM_SHOW" => $GLOBALS["arTheme"]["GALLERY_ITEM_SHOW"]["VALUE"],
                                                    "MAX_GALLERY_ITEMS" => $GLOBALS["arTheme"]["GALLERY_ITEM_SHOW"]["DEPENDENT_PARAMS"]["MAX_GALLERY_ITEMS"]["VALUE"],
                                                    "ADD_DETAIL_TO_GALLERY_IN_LIST" => $GLOBALS["arTheme"]["GALLERY_ITEM_SHOW"]["DEPENDENT_PARAMS"]["ADD_DETAIL_TO_GALLERY_IN_LIST"]["VALUE"],
                                                    'SHOW_CLOSE_POPUP' => isset($arParams['COMMON_SHOW_CLOSE_POPUP']) ? $arParams['COMMON_SHOW_CLOSE_POPUP'] : '',
                                                    'COMPARE_PATH' => $arResult['FOLDER'].$arResult['URL_TEMPLATES']['compare'],
                                                    "REVIEWS_VIEW" => $arParams["REVIEWS_VIEW"] == 'EXTENDED',
                                                    "COMPATIBLE_MODE" => "Y",
                                                ),
                                                false, array("HIDE_ICONS"=>"Y")
                                            );?>
                                            <?if ($blockViewType):?>
                                    </div>
                                    <?else:?>
                            </ul>
                        </div>
                        </li>
                        </ul>
                    <?endif;?>
                    </div>
                </div>
            <?endif;?>
                </div>
            <?endif;?>
            <div id="qv-mobile-props"></div>


            <?/*if($showProps && $arResult["GROUPS_PROPS"]):?>
                <?$strGrupperType = $arParams["GRUPPER_PROPS"];?>
                <div class="col-md-<?=$class;?>">
                    <h4><?=($arParams["TAB_CHAR_NAME"] ? $arParams["TAB_CHAR_NAME"] : GetMessage("PROPERTIES_TAB"));?></h4>
                    <?if($showProps):?>
                        <?if($strGrupperType == "GRUPPER"):?>
                            <div class="char_block">
                                <?$APPLICATION->IncludeComponent(
                                    "redsign:grupper.list",
                                    "hypower",
                                    Array(
                                        "CACHE_TIME" => "3600000",
                                        "CACHE_TYPE" => "A",
                                        "COMPOSITE_FRAME_MODE" => "A",
                                        "COMPOSITE_FRAME_TYPE" => "AUTO",
                                        "DISPLAY_PROPERTIES" => $arResult["GROUPS_PROPS"]
                                    ),
                                    $component, array('HIDE_ICONS'=>'Y')
                                );?>
                                <table class="props_list" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_DIV']; ?>"></table>
                            </div>
                        <?elseif($strGrupperType == "WEBDEBUG"):?>
                            <div class="char_block">
                                <?$APPLICATION->IncludeComponent(
                                    "webdebug:propsorter",
                                    "linear",
                                    array(
                                        "IBLOCK_TYPE" => $arResult['IBLOCK_TYPE'],
                                        "IBLOCK_ID" => $arResult['IBLOCK_ID'],
                                        "PROPERTIES" => $arResult['GROUPS_PROPS'],
                                        "EXCLUDE_PROPERTIES" => array(),
                                        "WARNING_IF_EMPTY" => "N",
                                        "WARNING_IF_EMPTY_TEXT" => "",
                                        "NOGROUP_SHOW" => "Y",
                                        "NOGROUP_NAME" => "",
                                        "MULTIPLE_SEPARATOR" => ", "
                                    ),
                                    $component, array('HIDE_ICONS'=>'Y')
                                );?>
                                <table class="props_list" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_DIV']; ?>"></table>
                            </div>
                        <?elseif($strGrupperType == "YENISITE_GRUPPER"):?>
                            <div class="char_block">
                                <?$APPLICATION->IncludeComponent(
                                    'yenisite:ipep.props_groups',
                                    '',
                                    array(
                                        'DISPLAY_PROPERTIES' => $arResult['GROUPS_PROPS'],
                                        'IBLOCK_ID' => $arParams['IBLOCK_ID']
                                    ),
                                    $component, array('HIDE_ICONS'=>'Y')
                                )?>
                                <table class="props_list colored_char" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_DIV']; ?>"></table>
                            </div>
                        <?else:?>
                            <?if($arParams["PROPERTIES_DISPLAY_TYPE"] != "TABLE"):?>
                                <div class="props_block" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_DIV']; ?>">
                                    <?foreach($arResult["PROPERTIES"] as $propCode => $arProp):?>
                                        <?if(isset($arResult["DISPLAY_PROPERTIES"][$propCode])):?>
                                            <?$arProp = $arResult["DISPLAY_PROPERTIES"][$propCode];?>
                                            <?if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE", "CML2_ARTICLE"))):?>
                                                <?if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))):?>
                                                    <div class="char" itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
                                                        <div class="char_name">
                                                            <?if($arProp["HINT"] && $arParams["SHOW_HINTS"]=="Y"):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?>
                                                            <div class="props_item <?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"){?>whint<?}?>">
                                                                <span itemprop="name"><?=$arProp["NAME"]?></span>
                                                            </div>
                                                        </div>
                                                        <div class="char_value" itemprop="value">
                                                            <?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
                                                                <?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
                                                            <?else:?>
                                                                <?=$arProp["DISPLAY_VALUE"];?>
                                                            <?endif;?>
                                                        </div>
                                                    </div>
                                                <?endif;?>
                                            <?endif;?>
                                        <?endif;?>
                                    <?endforeach;?>
                                </div>
                            <?else:?>
                                <div class="char_block">
                                    <table class="props_list">
                                        <?foreach($arResult["DISPLAY_PROPERTIES"] as $arProp):?>
                                            <?if(!in_array($arProp["CODE"], array("SERVICES", "BRAND", "HIT", "RECOMMEND", "NEW", "STOCK", "VIDEO", "VIDEO_YOUTUBE", "CML2_ARTICLE"))):?>
                                                <?if((!is_array($arProp["DISPLAY_VALUE"]) && strlen($arProp["DISPLAY_VALUE"])) || (is_array($arProp["DISPLAY_VALUE"]) && implode('', $arProp["DISPLAY_VALUE"]))):?>
                                                    <tr itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
                                                        <td class="char_name">
                                                            <?if($arProp["HINT"] && $arParams["SHOW_HINTS"]=="Y"):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp["HINT"]?></div></div><?endif;?>
                                                            <div class="props_item <?if($arProp["HINT"] && $arParams["SHOW_HINTS"] == "Y"){?>whint<?}?>">
                                                                <span itemprop="name"><?=$arProp["NAME"]?></span>
                                                            </div>
                                                        </td>
                                                        <td class="char_value">
                                                            <span itemprop="value">
                                                                <?if(count($arProp["DISPLAY_VALUE"]) > 1):?>
                                                                    <?=implode(', ', $arProp["DISPLAY_VALUE"]);?>
                                                                <?else:?>
                                                                    <?=$arProp["DISPLAY_VALUE"];?>
                                                                <?endif;?>
                                                            </span>
                                                        </td>
                                                    </tr>
                                                <?endif;?>
                                            <?endif;?>
                                        <?endforeach;?>
                                    </table>
                                    <table class="props_list" id="<? echo $arItemIDs["ALL_ITEM_IDS"]['DISPLAY_PROP_DIV']; ?>"></table>
                                </div>
                            <?endif;?>
                        <?endif;?>
                    <?endif;?>
                </div>
            <?endif;*/?>
        </div>
    <?endif;?>

    <?if($arResult['SECTION_DESCR']):?>
    <!--- table and title --->
    <div class="wraps">
        <hr>
        <div class="additional_block" id="dops">
            <?=htmlspecialchars_decode($arResult['SECTION_DESCR']);?>
        </div>
    </div>
    <?endif;?>
<?$this->EndViewTarget();?>


<?//additional gallery?>
<?$this->SetViewTarget('PRODUCT_ADDITIONAL_GALLERY_INFO');?>
    <?if($arResult['ADDITIONAL_GALLERY']):?>
        <div class="wraps galerys-block with-padding<?=($arResult['OFFERS'] && 'TYPE_1' === $arParams['TYPE_SKU'] ? ' hidden' : '')?>">
            <hr>
            <h4><?=($arParams["BLOCK_ADDITIONAL_GALLERY_NAME"] ? $arParams["BLOCK_ADDITIONAL_GALLERY_NAME"] : GetMessage("ADDITIONAL_GALLERY_TITLE"))?></h4>
            <?if($arParams['ADDITIONAL_GALLERY_TYPE'] === 'SMALL'):?>
                <div class="small-gallery-block">
                    <div class="flexslider unstyled front border small_slider custom_flex top_right color-controls" data-plugin-options='{"animation": "slide", "useCSS": true, "directionNav": true, "controlNav" :true, "animationLoop": true, "slideshow": false, "counts": [4, 3, 2, 1]}'>
                        <ul class="slides items">
                            <?if(!$arResult['OFFERS'] || 'TYPE_1' !== $arParams['TYPE_SKU']):?>
                                <?foreach($arResult['ADDITIONAL_GALLERY'] as $i => $arPhoto):?>
                                    <li class="col-md-3 item visible">
                                        <div>
                                            <img src="<?=$arPhoto['PREVIEW']['src']?>" class="img-responsive inline" title="<?=$arPhoto['TITLE']?>" alt="<?=$arPhoto['ALT']?>" />
                                        </div>
                                        <a href="<?=$arPhoto['DETAIL']['SRC']?>" class="fancy dark_block_animate" rel="gallery" target="_blank" title="<?=$arPhoto['TITLE']?>"></a>
                                    </li>
                                <?endforeach;?>
                            <?endif;?>
                        </ul>
                    </div>
                </div>
            <?else:?>
                <div class="gallery-block">
                    <div class="gallery-wrapper">
                        <div class="inner">
                            <?if(count($arResult['ADDITIONAL_GALLERY']) > 1 || ($arResult['OFFERS'] && 'TYPE_1' === $arParams['TYPE_SKU'])):?>
                                <div class="small-gallery-wrapper">
                                    <div class="flexslider unstyled small-gallery center-nav ethumbs" data-plugin-options='{"slideshow": false, "useCSS": true, "animation": "slide", "animationLoop": true, "itemWidth": 60, "itemMargin": 20, "minItems": 1, "maxItems": 9, "slide_counts": 1, "asNavFor": ".gallery-wrapper .bigs"}' id="carousel1">
                                        <ul class="slides items">
                                            <?if(!$arResult['OFFERS'] || 'TYPE_1' !== $arParams['TYPE_SKU']):?>
                                                <?foreach($arResult['ADDITIONAL_GALLERY'] as $arPhoto):?>
                                                    <li class="item">
                                                        <img class="img-responsive inline" src="<?=$arPhoto['THUMB']['src']?>" title="<?=$arPhoto['TITLE']?>" alt="<?=$arPhoto['ALT']?>" />
                                                    </li>
                                                <?endforeach;?>
                                            <?endif;?>
                                        </ul>
                                    </div>
                                </div>
                            <?endif;?>
                            <div class="flexslider big_slider dark bigs color-controls" id="slider" data-plugin-options='{"animation": "slide", "useCSS": true, "directionNav": true, "controlNav" :true, "animationLoop": true, "slideshow": false, "sync": "#carousel1"}'>
                                <ul class="slides items">
                                    <?if(!$arResult['OFFERS'] || 'TYPE_1' !== $arParams['TYPE_SKU']):?>
                                        <?foreach($arResult['ADDITIONAL_GALLERY'] as $i => $arPhoto):?>
                                            <li class="col-md-12 item">
                                                <a href="<?=$arPhoto['DETAIL']['SRC']?>" class="fancy" rel="gallery" target="_blank" title="<?=$arPhoto['TITLE']?>">
                                                    <img src="<?=$arPhoto['PREVIEW']['src']?>" class="img-responsive inline" title="<?=$arPhoto['TITLE']?>" alt="<?=$arPhoto['ALT']?>" />
                                                    <span class="zoom"></span>
                                                </a>
                                            </li>
                                        <?endforeach;?>
                                    <?endif;?>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            <?endif;?>
        </div>
    <?endif;?>
<?$this->EndViewTarget();?>


<?//files?>
<?$this->SetViewTarget('PRODUCT_FILES_INFO');?>
    <?$instr_prop = ($arParams["DETAIL_DOCS_PROP"] ? $arParams["DETAIL_DOCS_PROP"] : "INSTRUCTIONS");?>
<?if((!empty($arResult["PROPERTIES"][$instr_prop]["VALUE"]) && is_array($arResult["PROPERTIES"][$instr_prop]["VALUE"])) || !empty($arResult["SECTION_FULL"]["UF_FILES"])):?>
        <?
        $arFiles = array();
        if($arResult["PROPERTIES"][$instr_prop]["VALUE"]){
            $arFiles = $arResult["PROPERTIES"][$instr_prop]["VALUE"];
        }
        else{
            $arFiles = $arResult["SECTION_FULL"]["UF_FILES"];
        }
        if(is_array($arFiles)){
            foreach($arFiles as $key => $value){
                if(!intval($value)){
                    unset($arFiles[$key]);
                }
            }
        }
        if($arFiles):?>
            <div class="wraps">
                <hr>
                <h4><?=($arParams["BLOCK_DOCS_NAME"] ? $arParams["BLOCK_DOCS_NAME"] : GetMessage("DOCUMENTS_TITLE"))?></h4>
                <div class="files_block">
                    <div class="row flexbox">
                        <?foreach($arFiles as $arItem):?>
                            <div class="col-md-3 col-sm-6">
                                <?$arFile=CNext::GetFileInfo($arItem);?>
                                <div class="file_type clearfix <?=$arFile["TYPE"];?>">
                                    <i class="icon"></i>
                                    <div class="description">
                                        <a target="_blank" href="<?=$arFile["SRC"];?>" class="dark_link"><?=$arFile["DESCRIPTION"];?></a>
                                        <span class="size">
                                            <?=$arFile["FILE_SIZE_FORMAT"];?>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        <?endforeach;?>
                    </div>
                </div>
            </div>
        <?endif;?>
    <?endif;?>
<?$this->EndViewTarget();?>


<?//VIDEO?>
<?if($arResult['VIDEO']):?>
    <?$this->SetViewTarget('PRODUCT_VIDEO_INFO');?>
        <div class="wraps hidden_print">
            tretrtry
            <hr>
            <h4>
                <?=($arParams["TAB_VIDEO_NAME"] ? $arParams["TAB_VIDEO_NAME"] : GetMessage("VIDEO_TAB"));?>
                <?if(count($arResult['VIDEO']) > 1):?>
                    <span class="count empty">&nbsp;(<?=count($arResult['VIDEO'])?>)</span>
                <?endif;?>
            </h4>
            <div class="video_block">
                <?if(count($arResult['VIDEO']) > 1):?>
                    <table class="video_table">
                        <tbody>
                            <?foreach($arResult['VIDEO'] as $v => $value):?>
                                <?if(($v + 1) % 2):?>
                                    <tr>
                                <?endif;?>
                                <td width="50%"><?=str_replace('src=', 'width="458" height="257" src=', str_replace(array('width', 'height'), array('data-width', 'data-height'), $value));?></td>
                                <?if(!(($v + 1) % 2)):?>
                                    </tr>
                                <?endif;?>
                            <?endforeach;?>
                            <?if(($v + 1) % 2):?>
                                </tr>
                            <?endif;?>
                        </tbody>
                    </table>
                <?else:?>
                    <?=$arResult['VIDEO'][0]?>
                <?endif;?>
            </div>
        </div>
    <?$this->EndViewTarget();?>
<?endif;?>

<?//complect?>
<?$this->SetViewTarget('PRODUCT_KIT_BLOCK');?>
    <?if($arParams["SHOW_KIT_PARTS"] == "Y" && $arResult["SET_ITEMS"]):?>
        <div class="set_wrapp set_block drag_block_detail">
            <div class="title"><?=GetMessage("GROUP_PARTS_TITLE")?></div>
            <ul>
                <?foreach($arResult["SET_ITEMS"] as $iii => $arSetItem):?>
                    <li class="item">
                        <div class="item_inner">
                            <div class="image">
                                <a href="<?=$arSetItem["DETAIL_PAGE_URL"]?>">
                                    <?if($arSetItem["PREVIEW_PICTURE"]):?>
                                        <?$img = CFile::ResizeImageGet($arSetItem["PREVIEW_PICTURE"], array("width" => 140, "height" => 140), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
                                        <img  src="<?=$img["src"]?>" alt="<?=$arSetItem["NAME"];?>" title="<?=$arSetItem["NAME"];?>" />
                                    <?elseif($arSetItem["DETAIL_PICTURE"]):?>
                                        <?$img = CFile::ResizeImageGet($arSetItem["DETAIL_PICTURE"], array("width" => 140, "height" => 140), BX_RESIZE_IMAGE_PROPORTIONAL, true);?>
                                        <img  src="<?=$img["src"]?>" alt="<?=$arSetItem["NAME"];?>" title="<?=$arSetItem["NAME"];?>" />
                                    <?else:?>
                                        <img  src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_small.png" alt="<?=$arSetItem["NAME"];?>" title="<?=$arSetItem["NAME"];?>" />
                                    <?endif;?>
                                </a>
                                <?if($arResult["SET_ITEMS_QUANTITY"]):?>
                                    <div class="quantity">x<?=$arSetItem["QUANTITY"];?></div>
                                <?endif;?>
                            </div>
                            <div class="item_info">
                                <div class="item-title">
                                    <a href="<?=$arSetItem["DETAIL_PAGE_URL"]?>"><span><?=$arSetItem["NAME"]?></span></a>
                                </div>
                                <?if($arParams["SHOW_KIT_PARTS_PRICES"] == "Y"):?>
                                    <div class="cost prices clearfix">
                                        <?
                                        $arCountPricesCanAccess = 0;
                                        foreach($arSetItem["PRICES"] as $key => $arPrice){
                                            if($arPrice["CAN_ACCESS"]){
                                                $arCountPricesCanAccess++;
                                            }
                                        }

                                        if($arSetItem["MEASURE"][$arSetItem["ID"]]["MEASURE"]["SYMBOL_RUS"])
                                            $strMeasure = $arSetItem["MEASURE"][$arSetItem["ID"]]["MEASURE"]["SYMBOL_RUS"];
                                        ?>
                                        <?if(isset($arSetItem['PRICE_MATRIX']) && $arSetItem['PRICE_MATRIX']):?>
                                            <?
                                            // USE_PRICE_COUNT
                                            if($arSetItem['ITEM_PRICE_MODE'] == 'Q' && count($arSetItem['PRICE_MATRIX']['ROWS']) > 1){
                                                echo CNext::showPriceRangeTop($arSetItem, $arParams, GetMessage("CATALOG_ECONOMY"));
                                            }
                                            echo CNext::showPriceMatrix($arSetItem, $arParams, $strMeasure, $arAddToBasketData);
                                            ?>
                                        <?else:?>
                                            <?foreach($arSetItem["PRICES"] as $key => $arPrice):?>
                                                <?if($arPrice["CAN_ACCESS"]):?>
                                                    <?$price = CPrice::GetByID($arPrice["ID"]);?>
                                                    <?if($arCountPricesCanAccess > 1):?>
                                                        <div class="price_name"><?=$price["CATALOG_GROUP_NAME"];?></div>
                                                    <?endif;?>
                                                    <?if($arPrice["VALUE"] > $arPrice["DISCOUNT_VALUE"]  && $arParams["SHOW_OLD_PRICE"]=="Y"):?>
                                                        <div class="price">
                                                            <?=$arPrice["PRINT_DISCOUNT_VALUE"];?><?if(($arParams["SHOW_MEASURE"] == "Y") && $strMeasure):?><small>/<?=$strMeasure?></small><?endif;?>
                                                        </div>
                                                        <div class="price discount">
                                                            <span><?=$arPrice["PRINT_VALUE"]?></span>
                                                        </div>
                                                    <?else:?>
                                                        <div class="price">
                                                            <?=$arPrice["PRINT_VALUE"];?><?if(($arParams["SHOW_MEASURE"] == "Y") && $strMeasure):?><small>/<?=$strMeasure?></small><?endif;?>
                                                        </div>
                                                    <?endif;?>
                                                <?endif;?>
                                            <?endforeach;?>
                                        <?endif;?>
                                    </div>
                                <?endif;?>
                            </div>
                        </div>
                    </li>
                    <?if($arResult["SET_ITEMS"][$iii + 1]):?>
                        <li class="separator"></li>
                    <?endif;?>
                <?endforeach;?>
            </ul>
        </div>
    <?endif;?>
<?$this->EndViewTarget();?>

<?/*end buffers bloks for epilog*/?>



<script type="text/javascript">
    BX.message({
        QUANTITY_AVAILIABLE: '<? echo COption::GetOptionString("aspro.next", "EXPRESSION_FOR_EXISTS", GetMessage("EXPRESSION_FOR_EXISTS_DEFAULT"), SITE_ID); ?>',
        QUANTITY_NOT_AVAILIABLE: '<? echo COption::GetOptionString("aspro.next", "EXPRESSION_FOR_NOTEXISTS", GetMessage("EXPRESSION_FOR_NOTEXISTS"), SITE_ID); ?>',
        ADD_ERROR_BASKET: '<? echo GetMessage("ADD_ERROR_BASKET"); ?>',
        ADD_ERROR_COMPARE: '<? echo GetMessage("ADD_ERROR_COMPARE"); ?>',
        ONE_CLICK_BUY: '<? echo GetMessage("ONE_CLICK_BUY"); ?>',
        SITE_ID: '<? echo SITE_ID; ?>'
    })
</script>
<script type="text/javascript">
	  /* var player = document.getElementById('video-rutube');
	  var playerMobile = document.getElementById('video-rutube-mobile');
	  function doCommand(CommandJSON) {
			player.contentWindow.postMessage(JSON.stringify(CommandJSON), '*');
			playerMobile.contentWindow.postMessage(JSON.stringify(CommandJSON), '*');
		}

		function do_play()                { doCommand( {type:'player:play',           data:{}                                     } ); }
		function do_pause()               { doCommand( {type:'player:pause',          data:{}                                     } ); }
		function do_stop()                { doCommand( {type:'player:stop',           data:{}                                     } ); }
		function do_setCurrentTime()      { doCommand( {type:'player:setCurrentTime', data:{time:60}                              } ); }
		function do_relativelySeekRight() { doCommand( {type:'player:relativelySeek', data:{time:60}                              } ); }
		function do_relativelySeekLeft()  { doCommand( {type:'player:relativelySeek', data:{time:-60}                             } ); }
		function do_mute()                { doCommand( {type:'player:mute',           data:{}                                     } ); }
		function do_unMute()              { doCommand( {type:'player:unMute',         data:{}                                     } ); }
		function do_VolumeMid()           { doCommand( {type:'player:setVolume',      data:{volume: 0.5}                          } ); }
		function do_VolumeUp()            { doCommand( {type:'player:setVolume',      data:{change: 0.1}                          } ); }
		function do_VolumeDown()          { doCommand( {type:'player:setVolume',      data:{change: -0.1}                         } ); }
		function setVolume()              { doCommand( {type:'player:setVolume',      data:{volume: 0}                         } ); }
	
	 $(document).ready(function () {
		  // Вызывать mute только после первого взаимодействия с пользователем
		  let isMuted = false;

		  function initVideoControl() {
			if (!isMuted) {
			  do_mute();
			  setVolume();
			  isMuted = true;
			}
		  }

		  $('ul.slides_block li, ol.flex-control-nav li').on('click touchstart', function () {
			initVideoControl();

			if (!$(this).hasClass('video-slide')) {
			  do_pause();
			}
		  });
		}); */
// Находим все элементы с классом video-slide
const videoSlides = document.querySelectorAll('.video-slide');

if (videoSlides.length > 0) {

    function handleSlideChange() {
        videoSlides.forEach(slide => {
            const video = slide.querySelector('video');
            if (video) {
                if (slide.classList.contains('current') || slide.classList.contains('flex-active-slide')) {
 
                } else {

                    video.pause();
                    video.currentTime = 0;
                }
            }
        });
    }


    const observer = new MutationObserver((mutations) => {

        handleSlideChange();
    });


    videoSlides.forEach(slide => {
        observer.observe(slide, { attributes: true, attributeFilter: ['class'] });
    });


    handleSlideChange();
}
</script>
<style>
.low-price-right{
    float: right;
}
.low-price-mobile{
    display: none;
}
@media (max-width: 900px){
.low-price-right{
    display: none;
}

.low-price-mobile{
    display: block;
}

}

</style>


<script>
(function(){
  function setPosterOn(el, poster){
    if(!el || !poster) return;
    try{
      if(!el.getAttribute('poster')){
        el.setAttribute('poster', poster);
      }else{
        // if poster differs, update
        if(el.getAttribute('poster') !== poster){ el.setAttribute('poster', poster); }
      }
      el.setAttribute('preload', 'metadata');
      el.setAttribute('playsinline', '');
      // If not started yet, refresh frame so poster becomes visible
      if (el.readyState > 0 && el.paused) {
        try { el.load(); } catch(e){}
      }
    }catch(e){}
  }

  function applyPosterNow(){
    var poster = (window.__videoPoster || '').toString();
    if(!poster) return;
    document.querySelectorAll('.video-slide video').forEach(function(v){ setPosterOn(v, poster); });
  }

  // Run immediately after current microtask to ensure gallery markup exists
  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function(){ setTimeout(applyPosterNow, 0); });
  } else {
    setTimeout(applyPosterNow, 0);
  }

  // Observe for slides changing / re-render
  var gallery = document.querySelector('.item_slider');
  if (gallery && 'MutationObserver' in window) {
    var mo = new MutationObserver(function(){
      applyPosterNow();
    });
    mo.observe(gallery, { childList: true, subtree: true });
  }

  // Also re-apply on thumb clicks
  document.addEventListener('click', function(e){
    if(e.target.closest && e.target.closest('.item_slider .thumbs .slides_block li')){
      setTimeout(applyPosterNow, 0);
    }
  });
})();
</script>


<script>
(function(){
  function setPosterOn(el, poster){
    if(!el || !poster) return;
    try{
      if(!el.getAttribute('poster')){
        el.setAttribute('poster', poster);
      }else if(el.getAttribute('poster') !== poster){
        el.setAttribute('poster', poster);
      }
      el.setAttribute('preload', 'metadata');
      el.setAttribute('playsinline', '');

      // Hide native controls initially
      el.controls = false;
      el.removeAttribute('controls');
      el.classList.add('video--clickable');

      // Ensure poster is shown
      try { if (el.readyState > 0 && el.paused) el.load(); } catch(e){}

      // Click to play: enable controls and start playback
      if (!el.dataset._coverHandlerBound) {
        el.addEventListener('click', function(){
          try {
            el.setAttribute('controls','');
            el.controls = true;
            el.play && el.play();
          } catch(e){}
        });
        el.dataset._coverHandlerBound = '1';
      }
    }catch(e){}
  }

  function applyPosterNow(){
    var poster = (window.__videoPoster || '').toString();
    if(!poster) return;
    document.querySelectorAll('.video-slide video').forEach(function(v){ setPosterOn(v, poster); });
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', function(){ setTimeout(applyPosterNow, 0); });
  } else {
    setTimeout(applyPosterNow, 0);
  }

  var gallery = document.querySelector('.item_slider');
  if (gallery && 'MutationObserver' in window) {
    var mo = new MutationObserver(function(){ applyPosterNow(); });
    mo.observe(gallery, { childList: true, subtree: true });
  }

  document.addEventListener('click', function(e){
    if(e.target.closest && e.target.closest('.item_slider .thumbs .slides_block li')){
      setTimeout(applyPosterNow, 0);
    }
  });
})();
</script>
<style>
  /* Optional: cursor hint */
  .video--clickable { cursor: pointer; }
</style>


<script>
(function(){
  function wrap(el, wrapper){
    el.parentNode.insertBefore(wrapper, el);
    wrapper.appendChild(el);
  }

  function ensurePlayButton(container){
    if (container.querySelector('.video-cover-playbtn')) return container.querySelector('.video-cover-playbtn');
    var btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'video-cover-playbtn';
    btn.setAttribute('aria-label', 'Смотреть видео');
    container.appendChild(btn);
    return btn;
  }

  function enhanceVideo(el, poster){
    if(!el) return;
    try{
      if (poster) el.setAttribute('poster', poster);
      el.setAttribute('preload', 'metadata');
      el.setAttribute('playsinline', '');
      el.removeAttribute('controls');

      var parent = el.parentElement;
      // Prefer li.video-slide as container if exists
      if (parent && parent.tagName && !parent.classList.contains('video-cover-box')){
        // If parent is not positioned, wrap to avoid breaking slider markup
        var positioned = window.getComputedStyle(parent).position;
        if (positioned !== 'relative' && positioned !== 'absolute' && positioned !== 'fixed'){
          var box = document.createElement('div');
          box.className = 'video-cover-box';
          wrap(el, box);
          parent = box;
        } else {
          parent.classList.add('video-cover-box');
        }
      }

      var btn = ensurePlayButton(parent);
      if (btn && !el.dataset._pbAttached){
        var start = function(){
          btn.style.display = 'none';
          el.setAttribute('controls','');
          el.controls = true;
          try { el.play(); } catch(e){}
        };
        btn.addEventListener('click', function(e){ e.preventDefault(); start(); }, { once:true });
        el.addEventListener('click', function(){ if (btn.style.display !== 'none') start(); }, { once:true });
        el.dataset._pbAttached = '1';
      }
    }catch(e){}
  }

  function applyToAll(){
    var poster = (window.__videoPoster || '').toString();
    var nodes = document.querySelectorAll('.video-slide video, .product-video, .product-video-iframe + video');
    nodes.forEach(function(v){ enhanceVideo(v, poster); });
  }

  if (document.readyState === 'loading'){
    document.addEventListener('DOMContentLoaded', applyToAll);
  } else {
    applyToAll();
  }

  var gallery = document.querySelector('.item_slider');
  if (gallery && 'MutationObserver' in window){
    new MutationObserver(function(){ applyToAll(); })
      .observe(gallery, {childList:true, subtree:true});
  }
})();
</script>

<style>
.video-cover-box{
  position: relative;
  display: block;
  width: 100%;
}
.video-cover-box > video{
  display: block;
  width: 100%;
  height: auto;
}

/* Play button */
.video-cover-playbtn{
  all: unset;
  position: absolute;
  top: 50%; left: 50%;
  transform: translate(-50%, -50%);
  width: 74px; height: 74px;
  border-radius: 50%;
  background: rgba(0,0,0,.55);
  box-shadow: 0 10px 24px rgba(0,0,0,.25);
  cursor: pointer;
  z-index: 3;
}
.video-cover-playbtn::before{
  content: "";
  position: absolute;
  top: 50%; left: 50%;
  transform: translate(-35%,-50%);
  width: 0; height: 0;
  border-left: 24px solid #fff;
  border-top: 15px solid transparent;
  border-bottom: 15px solid transparent;
}
.video-cover-playbtn:focus-visible{
  outline: 2px solid #fff;
  outline-offset: 3px;
}
</style>


<script>
(function(){
  function wrapOverlayForRutube(ifr){
    if(!ifr || ifr.dataset._rtbDone) return;

    var li = ifr.closest('li.video-slide') || ifr.parentElement;
    if(!li) return;
    li.style.position = li.style.position || 'relative';
    var h = ifr.clientHeight || parseInt(ifr.getAttribute('height')) || 0;
    if(h) li.style.minHeight = h + 'px';

    var orig = ifr.getAttribute('src') || '';
    if(!orig) return;
    if(!ifr.dataset.origSrc) ifr.dataset.origSrc = orig;
    try { ifr.setAttribute('src',''); } catch(e){}
    ifr.style.display = 'none';
    ifr.style.pointerEvents = 'none';

    var poster = (window.__videoPoster || '').toString();
    var overlay = document.createElement('div');
    overlay.className = 'media-cover-overlay';
    overlay.style.zIndex = '3';

    if(poster){
      var img = document.createElement('img');
      img.className = 'media-cover-img';
      img.src = poster;
      overlay.appendChild(img);
    }
    var btn = document.createElement('button');
    btn.type = 'button';
    btn.className = 'media-cover-playbtn';
    btn.setAttribute('aria-label','Смотреть видео');
    overlay.appendChild(btn);

    li.appendChild(overlay);

    function addAutoplay(src){
      try{
        var u = new URL(src, location.href);
        u.searchParams.set('autoplay','1');
        u.searchParams.delete('mute');
        return u.toString();
      }catch(e){
        var sep = src.indexOf('?') === -1 ? '?' : '&';
        return src + sep + 'autoplay=1';
      }
    }

    var start = function(e){
      if(e) e.preventDefault();
      var src = addAutoplay(ifr.dataset.origSrc || orig);
      try { ifr.setAttribute('src', src); } catch(e){}
      ifr.style.display = '';
      ifr.style.pointerEvents = '';
      overlay.remove();
    };

    overlay.addEventListener('click', start, { once: true });

    ifr.dataset._rtbDone = '1';
  }

  function run(){
    document.querySelectorAll(
      'li.video-slide iframe#video-rutube,' +
      'li.video-slide iframe#video-rutube-mobile,' +
      'li.video-slide iframe[src*="rutube.ru/play/embed"]'
    ).forEach(wrapOverlayForRutube);
  }

  if(document.readyState === 'loading'){
    document.addEventListener('DOMContentLoaded', run);
  } else {
    run();
  }

  var gallery = document.querySelector('.item_slider');
  if(gallery && 'MutationObserver' in window){
    new MutationObserver(run).observe(gallery, {childList:true, subtree:true});
  }
})();
</script>

<style>
.media-cover-overlay{
  position:absolute;
  inset:0;
  display:grid;
  place-items:center;
}
.media-cover-img{
  position:absolute;
  inset:0;
  width:100%;
  height:100%;
  object-fit:cover;
}
.media-cover-playbtn{
  all:unset;
  width:74px; height:74px;
  border-radius:50%;
  background:rgba(0,0,0,.55);
  box-shadow:0 10px 24px rgba(0,0,0,.25);
  cursor:pointer;
  z-index:4;
}
.media-cover-playbtn::before{
  content:"";
  position:relative;
  left:6px;
  display:inline-block;
  width:0; height:0;
  border-left:24px solid #fff;
  border-top:15px solid transparent;
  border-bottom:15px solid transparent;
}
</style>
