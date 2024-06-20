<?php

declare(strict_types=1);

namespace App\Message;

readonly class GetEvent
{
    public function __construct(
        public string $name = ''
    ) {
    }
}
