<?php

namespace App\Services\Helpers;

use DateTime;

/**
 * Description of DateTimeManager
 *
 * @author Hristo
 */
class DateTimeManager
{
    public const DATE_FORMAT = 'Y-m-d';

    public const DATE_TIME_FORMAT = 'Y-m-d H:i:s';

    public static function now(): DateTime
    {
        return new DateTime('NOW');
    }

    public static function nowStr(): string
    {
        return self::now()->format(self::DATE_TIME_FORMAT);
    }

    public static function isValidDate(string $date, string $format = self::DATE_FORMAT): bool
    {
        return self::isValidDateTime($date, $format);
    }

    public static function isValidDateTime(string $dateTime, string $format = self::DATE_TIME_FORMAT): bool
    {
        $dateTimeObj = DateTime::createFromFormat($format, $dateTime);

        return $dateTimeObj && $dateTimeObj->format($format) === $dateTime;
    }
}
