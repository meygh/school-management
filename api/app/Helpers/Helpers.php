<?php

function numbersInEnglish($string) {
    $persian = ['۰', '۱', '۲', '۳', '۴', '۵', '۶', '۷', '۸', '۹'];
    $arabic = ['٠', '١', '٢', '٣', '٤', '٥', '٦', '٧', '٨', '٩'];

    $num = range(0, 9);
    $convertedPersianNums = str_replace($persian, $num, $string);
    $englishNumbersOnly = str_replace($arabic, $num, $convertedPersianNums);

    return $englishNumbersOnly;
}

function dateFormat($date,$format){
    return \Carbon\Carbon::createFromFormat('Y-m-d', $date)->format($format);
}

function trimString($string, $repl, $limit)
{
    if(strlen($string) > $limit)
    {
        return substr($string, 0, $limit) . $repl;
    }
    else
    {
        return $string;
    }
}

function truncate($string, $length)
{
    if (strlen($string) > $length) {
        return substr($string, 0, $length - 3) . '...';
    }
    return $string;
}
