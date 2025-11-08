<?php

namespace Models;

use Bitrix\Main\Loader;

Loader::includeModule('iblock');

class ProcedureTable extends AbstractIblockPropertyValuesTable
{
    const IBLOCK_ID = null;

    /**
     * Получает ID инфоблока procedures
     */
    public static function getIblockId()
    {
        if (static::IBLOCK_ID) {
            return static::IBLOCK_ID;
        }

        $iblock = \Bitrix\Iblock\IblockTable::getList([
            'filter' => ['CODE' => 'procedures'],
            'select' => ['ID']
        ])->fetch();

        return $iblock ? $iblock['ID'] : null;
    }


    /**
     * @return array
     */
    public static function getAllProcedures()
    {
        $iblockId = self::getIblockId();
        if (!$iblockId) return [];

        $entityClass = \Bitrix\Iblock\Iblock::wakeUp($iblockId)->getEntityDataClass();

        return $entityClass::getList([
            'filter' => ['ACTIVE' => 'Y'],
            'select' => ['ID', 'NAME'],
            'order' => ['NAME' => 'ASC']
        ])->fetchAll();
    }


    /**
     * @param $name
     * @return string
     */
    public static function addProcedure($name)
    {
        $iblockId = self::getIblockId();
        if (!$iblockId) {
            return "Ошибка: Инфоблок процедур не найден";
        }

        $entityClass = \Bitrix\Iblock\Iblock::wakeUp($iblockId)->getEntityDataClass();

        try {
            $result = $entityClass::add([
                'NAME' => $name,
                'IBLOCK_ID' => $iblockId,
                'ACTIVE' => 'Y'
            ]);

            if ($result->isSuccess()) {
                return $result->getId();
            } else {
                $errors = $result->getErrorMessages();
                return "Ошибка добавления процедуры: " . implode(', ', $errors);
            }

        } catch (\Exception $e) {
            return "Ошибка добавления процедуры: " . $e->getMessage();
        }
    }
}