<?php

declare(strict_types=1);

namespace App\ActorSystem;

use Phluxor\ActorSystem;

readonly class AppActor
{
    public const string NAME = 'box_office';

    public function __construct(
        public ActorSystem\RootContext $root,
        public ActorSystem\Ref $actorRef
    ) {
    }
}
