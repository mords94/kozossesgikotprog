<?php

namespace Library;

class DateMy
{

    const mysqlFormat = "Y-m-d H:i:s";
    const myFormat = "Y-m-d H:i";

    public static function alt_diffInSeconds($start, $end)
    {
        $start_date = new DateTime($start);
        $end_date = new DateTime($end);
        $interval = $start_date->diff($end_date);
        $hours = $interval->format('%h');
        $minutes = $interval->format('%i');
        $seconds = $interval->format('%s');
        return ((intval($hours) * 60 + intval($minutes)) * 60 + intval($seconds));
    }

    public static function diffInSeconds($start, $end) {
        $start_date = new DateTime($start);
        $end_date = new DateTime($end);

        return ($end_date->getTimestamp() - $start_date->getTimestamp());
    }

    public static function humanTiming ($time)
    {

        $time = time() - $time; // to get the time since that moment
        $time = ($time<1)? 1 : $time;
        $tokens = array (
            31536000 => 'éve',
            2592000 => 'hónapja',
            604800 => 'hete',
            86400 => 'napja',
            3600 => 'órája',
            60 => 'perce',
            1 => 'másodperce'
        );

        foreach ($tokens as $unit => $text) {
            if ($time < $unit) continue;
            $numberOfUnits = floor($time / $unit);
            return $numberOfUnits.' '.$text;
        }

    }


    public static function thisYear()
    {
        $date = new DateTime();
        return $date->format("Y");
    }

    public static function now() {
        $date = new DateTime();
        return $date->format(self::myFormat);
    }

    public static function currentTimestamp() {
        $date = new DateTime();
        return $date->format(self::mysqlFormat);
    }
}