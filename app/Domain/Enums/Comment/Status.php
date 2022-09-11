<?php

namespace App\Domain\Enums\Comment;

enum Status: int
{
    case Unapproved = 0;
    case Approved = 1;
    case Rejected = 2;

    public static function toArray(): array
    {
        $values = [];
        foreach (self::cases() as $case) {
            $values[$case->name] = $case->value;
        }

        return $values;
    }
}
