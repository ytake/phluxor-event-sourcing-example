<?php

declare(strict_types=1);

namespace App\Event;

readonly class EventCreated
{
    public function __construct(
        public string $name,
        public int $tickets
    ) {
    }
}
