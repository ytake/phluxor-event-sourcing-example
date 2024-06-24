<?php

declare(strict_types=1);

namespace App\Message;

use Phluxor\ActorSystem\Ref;

class Add
{
    public function __construct(
        readonly public string $name,
        readonly public int $tickets,
        readonly public Ref $replyTo
    ) {
    }
}
