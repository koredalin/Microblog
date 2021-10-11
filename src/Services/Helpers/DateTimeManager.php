<?php

namespace App\Services\Helpers;

use App\Common\Interfaces\DateTimeManagerInterface;

/**
 * Description of DateTimeManager
 *
 * @author Hristo
 */
class DateTimeManager implements DateTimeManagerInterface
{
    const DATE_FORMAT = 'Y-m-d';
    
    const DATE_TIME_FORMAT = 'Y-m-d H:i:s';
    
    public static function now(): \DateTime
    {
        return new \DateTime('NOW');
    }
    
    public static function nowStr(): string
    {
        return self::now()::format(self::DATE_TIME_FORMAT);
    }
    
    public static function validateDate(string $date, string $format = self::DATE_FORMAT): bool
    {
        return self::validateDateTime($date, $format);
    }
    
    public static function validateDateTime(string $date, string $format = self::DATE_TIME_FORMAT): bool
    {
        $dateTime = DateTime::createFromFormat($format, $date);
        
        return $dateTime && $dateTime->format($format) === $date;
    }
}
