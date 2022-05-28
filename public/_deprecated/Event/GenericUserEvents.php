<?php

namespace App\Event;

use Symfony\Contracts\EventDispatcher\Event;

final class GenericUserEvents
{
    public const REQUEST_RESET_LIMITER = 'custom.request_reset_limiter';

    public const ALIASES = [
        Event::class => self::REQUEST_RESET_LIMITER,
    ];
}
