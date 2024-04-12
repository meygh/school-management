<?php

namespace App\Enums;

enum Status: int
{
    case INACTIVE = -1;
    case PENDING = 0;
    case ACTIVE = 10;

    public function label(): string
    {
        return match($this) {
            self::INACTIVE => __('user.Inactive'),
            self::PENDING => __('user.Pending'),
            self::ACTIVE => __('user.Active'),
            default => throw new \Exception('Unexpected status value'),
        };
    }
}
