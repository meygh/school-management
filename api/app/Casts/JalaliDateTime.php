<?php

namespace App\Casts;

use Carbon\Carbon;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Morilog\Jalali\CalendarUtils;
use Morilog\Jalali\Jalalian;

class JalaliDateTime implements CastsAttributes
{
    protected $format = 'Y/m/d H:i:s';
    protected $toFormat = 'Y-m-d H:i:s';

    public function format(?string $value = null)
    {
        if ($value) {
            $this->format = $value;
        }

        return $this->format;
    }

    public function get($model, $key, $value, $attributes)
    {
        if (!$value) {
            return $value;
        }

        if (is_string($value) || is_numeric($value)) {
            $value = Carbon::createFromTimeString($value);
        }

        return Jalalian::fromCarbon($value)->format($this->format);
    }

    public function set($model, $key, $value, $attributes)
    {
        if (!$value || !strstr($value, '/')) {
            return $value;
        }

        return CalendarUtils::createCarbonFromFormat($this->format, $value)
            ->format($this->toFormat);
    }
}
