<?php

namespace App\Domain\Entities;

abstract class DomainEntity
{
    public function __construct($values)
    {
        foreach ($values as $key => $value) {
            $this->$key = $value;
        }
    }
}
