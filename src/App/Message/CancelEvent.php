<?php

declare(strict_types=1);

namespace App\Message;

readonly class CancelEvent
{
    public function __construct(
        public string $name
    ) {
    }
}
