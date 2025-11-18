<? if (!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();
/** @var array $arCurrentValues */

if(!CModule::IncludeModule("iblock"))
    return;

$arComponentParameters = array(
    "GROUPS" => array(
        "LIST"=>array(
            "NAME"=>GetMessage("GRID_PARAMETERS"),
            "SORT"=>"300"
        )
    ),
    "PARAMETERS" => array(
        "CURRENCY" =>  array(
            "PARENT" => "LIST",
            "NAME"=>GetMessage("CURRENCY"),
            "TYPE"=>"LIST",
            "VALUES"=> array(
                "RUB" => GetMessage("CURRENCY_RUB"),
                "USD" => GetMessage("CURRENCY_USD"),
                "EUR" => GetMessage("CURRENCY_EUR"),
                "UAH" => GetMessage("CURRENCY_UAH"),
                "BYN" => GetMessage("CURRENCY_BYN")
            ),
            "DEFAULT"=>"",
        ),
    )
);
?>