<?php

namespace App\Enums;

enum UserStatus: int
{
    case INACTIVE = -1;
    case VISITOR = 0;
    case STUDENT = 10;
    case TEACHER = 20;
    case PRINCIPLE = 30;
    case ADMIN = 1;
    case SUPER_ADMIN = 2;

    public function label(): string
    {
        return self::getLabel($this);
    }

    public static function getLabel(self $case): string
    {
        return match($case) {
            self::INACTIVE => __('user.Inactive'),
            self::VISITOR => __('user.Visitor'),
            self::STUDENT => __('user.SchoolStudent'),
            self::TEACHER => __('user.Teacher'),
            self::PRINCIPLE => __('user.Principle'),
            self::ADMIN => __('user.Admin'),
            self::SUPER_ADMIN => __('user.SuperAdmin'),
        };
    }
}
