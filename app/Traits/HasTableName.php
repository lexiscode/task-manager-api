<?php

declare(strict_types=1);

namespace App\Traits;

trait HasTableName
{
    /** {@inheritdoc} */
    final public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
    }

    public static function getTableName(): string
    {
        return (new static)->getTable();
    }
}

