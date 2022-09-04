<?php

namespace App\Enums\Domains\Post;

enum Status: int
{
    case Unapproved = 0;
    case Approved = 1;
    case Rejected = 2;

    public static function toArray(): array
    {
        $values = [];
        foreach (self::cases() as $case) {
            $values[] = $case->value;
        }

        return $values;
    }
}
