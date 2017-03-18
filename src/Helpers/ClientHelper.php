<?php

namespace Watson\Helpers;

use Monolog\Logger;
use Monolog\Handler\StreamHandler;
use Keboola\Csv\CsvFile;
use Keboola\Csv\Exception as KeboolaCsvException;

class ClientHelper
{

    public static function buildRequestUrl($serviceUrl,$serviceVersion,$serviceEndpoint){

        return sprintf('%s/%s/%s',$serviceUrl,$serviceVersion,$serviceEndpoint);

    }

    /**
     * @param string $logFile
     * @param $message
     * @param $level
     *
     * DEBUG (100): Detailed debug information.
     * INFO (200): Interesting events. Examples: User logs in, SQL logs.
     * NOTICE (250): Normal but significant events.
     * WARNING (300): Exceptional occurrences that are not errors. Examples: Use of deprecated APIs, poor use of an API, undesirable things that are not necessarily wrong.
     * ERROR (400): Runtime errors that do not require immediate action but should typically be logged and monitored.
     * CRITICAL (500): Critical conditions. Example: Application component unavailable, unexpected exception.
     * ALERT (550): Action must be taken immediately. Example: Entire website down, database unavailable, etc. This should trigger the SMS alerts and wake you up.
     * EMERGENCY (600): Emergency: system is unusable.
     *
     */

    public static function log($message,$level,$logFile = 'general'){

        $logger = new Logger('watson');
        $logger->pushHandler(new StreamHandler("../{$logFile}.log", $level));

        switch($level){
            case 100:
                $logger->addDebug($message);
                break;
            case 200:
                $logger->addInfo($message);
                break;
            case 250:
                $logger->addNotice($message);
                break;
            case 300:
                $logger->addWarning($message);
                break;
            case 400:
                $logger->addError($message);
                break;
            case 500:
                $logger->addCritical($message);
                break;
            case 550:
                $logger->addAlert($message);
                break;
            case 600:
                $logger->addEmergency($message);
                break;
            default:
                $logger->addInfo($message);
                break;

        }

    }

    /**
     * Read CSV Files to an array
     * @param $inputFilePath
     * @return array
     */
    public static function readCsv($inputFilePath){

        $rows = array();

        try{
            $csvFile = new CsvFile($inputFilePath);

            foreach($csvFile as $row) {
                array_push($rows,$row);
            }

        }catch(KeboolaCsvException $e){

            self::log($e->getMessage(), Logger::CRITICAL);

        }catch(\Exception $e){

            self::log($e->getMessage(), Logger::CRITICAL);

        }

        return $rows;

    }


    /**
     * @param $outputFilePath
     * @param array $rows
     */
    public static function writeCsv($outputFilePath,array $rows = []){

        try{

            $csvFile = new CsvFile($outputFilePath);

            foreach ($rows as $row) {
                $csvFile->writeRow($row);
            }

        }catch(KeboolaCsvException $e){

            self::log($e->getMessage(), Logger::CRITICAL);

        }catch(\Exception $e){

            self::log($e->getMessage(), Logger::CRITICAL);

        }

    }

}