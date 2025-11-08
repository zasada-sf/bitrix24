<?php

namespace Models;

use Bitrix\Main\Loader;

Loader::includeModule('iblock');

class DoctorTable extends AbstractIblockPropertyValuesTable
{
    const IBLOCK_ID = null;
    const PROCEDURES_PROPERTY_ID = 68; // Жестко задаем ID свойства


    /**
     * @return null
     */
    public static function getIblockId()
    {
        if (static::IBLOCK_ID) {
            return static::IBLOCK_ID;
        }

        $iblock = \Bitrix\Iblock\IblockTable::getList([
            'filter' => ['CODE' => 'doctors'],
            'select' => ['ID']
        ])->fetch();

        return $iblock ? $iblock['ID'] : null;
    }


    /**
     * @return array
     */
    public static function getAllDoctors()
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
     * @param $doctorId
     * @return array
     */
    public static function getDoctorProcedures($doctorId)
    {
        $iblockId = self::getIblockId();
        if (!$iblockId) return [];

        $entityClass = \Bitrix\Iblock\Iblock::wakeUp($iblockId)->getEntityDataClass();

        try {
            $doctors = $entityClass::query()
                ->setSelect([
                    'NAME',
                    'PROCEDURES.ELEMENT.NAME',
                ])
                ->setFilter(['ACTIVE' => 'Y', 'ID' => $doctorId])
                ->fetchCollection();

            $procedures = [];
            foreach ($doctors as $doctor){
                foreach($doctor->getProcedures()->getAll() as $procedureItem) {
                    $procedures[] = [
                        'NAME'=> $procedureItem->getElement()->getName(),
                        'ID' => $procedureItem->getElement()->getId()
                    ];
                }
            }

            return $procedures;
        } catch (\Exception $e) {
            error_log("Ошибка получения процедур врача: " . $e->getMessage());
            return [];
        }
    }


    /**
     * @param $name
     * @param $procedureIds
     * @return string
     */
    public static function addDoctor($name, $procedureIds = [])
    {
        $iblockId = self::getIblockId();
        if (!$iblockId) {
            return "Ошибка: Инфоблок врачей не найден";
        }

        // Используем CIBlockElement для надежного добавления с свойствами
        $element = new \CIBlockElement;

        $fields = [
            'NAME' => $name,
            'IBLOCK_ID' => $iblockId,
            'ACTIVE' => 'Y',
        ];

        // Добавляем свойства процедур
        if (!empty($procedureIds)) {
            $fields['PROPERTY_VALUES'] = [
                'PROCEDURES' => $procedureIds
            ];
        }

        $result = $element->Add($fields);

        if (!$result) {
            global $APPLICATION;
            $error = $APPLICATION->GetException();
            return "Ошибка добавления врача: " . ($error ? $error->GetString() : $element->LAST_ERROR);
        }

        return $result;
    }
}