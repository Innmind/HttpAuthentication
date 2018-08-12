<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaStorage;

use Innmind\HttpAuthentication\Identity;

final class InMemory implements Storage
{
    private $identity;

    public function get(): Identity
    {
        return $this->identity;
    }

    public function has(): bool
    {
        return $this->identity instanceof Identity;
    }

    public function set(Identity $identity): void
    {
        $this->identity = $identity;
    }
}
