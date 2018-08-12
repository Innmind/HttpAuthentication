<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaStorage;

use Innmind\HttpAuthentication\Identity;

final class NullStorage implements Storage
{
    public function get(): Identity
    {
        // let throw a TypeError
    }

    public function has(): bool
    {
        return false;
    }

    public function set(Identity $identity): void
    {
        // pass
    }
}
