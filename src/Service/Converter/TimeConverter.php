<?php
declare(strict_types=1);

namespace Orcano\Service\Converter;

class TimeConverter
{
    public static function millisecondsToTime(float $inputMs): string 
    {
        $milliseconds = (int) $inputMs % 1000;
        $seconds = (int) ($inputMs / 1000) % 60;
        $minutes = (int) (($inputMs / (1000 * 60)) % 60);
        $hours = (int) (($inputMs / (1000 * 60 * 60)) % 24);

        $sections = [
            'hour' => $hours,
            'min' => $minutes,
            'sec' => $seconds,
            'ms' => $milliseconds,
        ];

        $timeParts = [];

        foreach ($sections as $name => $value){
            if ($value > 0){
                $timeParts[] = $value . ' ' . $name;
            }
        }

        return implode(', ', $timeParts);
    }
}
