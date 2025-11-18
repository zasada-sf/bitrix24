<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true) die();

use Bitrix\Main\Loader;
use Bitrix\Currency\CurrencyTable;

Loader::includeModule('currency');

class TableCurrenciesComponent extends \CBitrixComponent
{
    public function onPrepareComponentParams($arParams) {
        $arParams['CURRENT_CURRENCY'] = trim($arParams['CURRENCY'] ?? '');
        return $arParams;
    }

    public function executeComponent() {
        $result = CurrencyTable::getList([
            'select' => ['CURRENCY', 'AMOUNT', 'AMOUNT_CNT', 'SORT', 'BASE'],
            'order'  => ['SORT' => 'ASC']
        ]);

        $this->arResult = [];
        while ($currency = $result->fetch()) {
            $current_currency = [
                'CURRENCY' => $currency['CURRENCY'],
                'AMOUNT' => $currency['AMOUNT'],
                'AMOUNT_CNT' => $currency['AMOUNT_CNT'],
                'SORT' => $currency['SORT'],
                'BASE' => $currency['BASE']
            ];
            $this->arResult[] = $current_currency;
        }

        $this->IncludeComponentTemplate();
    }
}