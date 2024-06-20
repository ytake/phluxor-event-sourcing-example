<?php

declare(strict_types=1);

namespace App\Message;

class Add
{
    public function __construct(
        readonly public string $name,
        readonly public int $tickets
    ) {
    }
}
