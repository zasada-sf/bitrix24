<?php require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/header.php');

$APPLICATION->SetTitle("Валюты");?><?$APPLICATION->IncludeComponent(
	"otus:currencies",
	"",
	Array(
		"CURRENCY" => ""
	)
);?><?php require_once($_SERVER['DOCUMENT_ROOT'].'/bitrix/footer.php');?>