<?php

use Bitrix\Main\Diag\ExceptionHandlerFormatter;
use Bitrix\Main\Diag\FileExceptionHandlerLog;

class FileExceptionFileLogCustom extends FileExceptionHandlerLog
{
    private $level;

    /**
     * @param $exception
     * @param $logType
     * @return void
     */
    public function write($exception, $logType)
    {
        $text = ExceptionHandlerFormatter::format($exception, false, $this->level);

        $context = [
            'type' => static::logTypeToString($logType),
        ];

        $logLevel = static::logTypeToLevel($logType);


        $message = "OTUS - {date} - Host: {host} - {type} - {$text}\n";

        $this->logger->log($logLevel, $message, $context);
    }
}