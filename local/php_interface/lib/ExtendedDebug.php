<?php

use Bitrix\Main\Diag\Debug;

class ExtendedDebug extends Debug
{
    protected static $logFile = "/otus/debug/page_access.txt";


    /**
     * @param $fileName
     * @param $extraData
     * @return void
     */
    public static function logPageAccess($fileName = "", $extraData = [])
    {
        if (empty($fileName)) {
            $fileName = self::$logFile;
        }

        $log = date('Y-m-d H:i:s');

        if (!empty($extraData)) {
            $log .= " - " . print_r($extraData, true);
        }

        parent::writeToFile($log, "", $fileName);
    }
}
