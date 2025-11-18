<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

// Если выбрана конкретная валюта - фильтруем массив
if (!empty($arParams['CURRENCY'])) {
    $filteredResult = [];
    foreach ($arResult as $currency) {
        if ($currency['CURRENCY'] === $arParams['CURRENCY']) {
            $filteredResult[] = $currency;
            break;
        }
    }
    $arResult = $filteredResult;
}

// Выводим результат
foreach ($arResult as $currency):?>
    <div>
        Валюта: <?=$currency['CURRENCY']?><br>
        Курс: <?=$currency['AMOUNT']?><br>
        Номинал: <?=$currency['AMOUNT_CNT']?><br>
        Базовая: <?=$currency['BASE']?><br>
    </div>
<?endforeach;?>

