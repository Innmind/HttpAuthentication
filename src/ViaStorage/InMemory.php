<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaStorage;

use Innmind\HttpAuthentication\Identity;
use Innmind\Http\Message\ServerRequest;

final class InMemory implements Storage
{
    private ?Identity $identity = null;

    public function get(ServerRequest $request): Identity
    {
        return $this->identity;
    }

    public function has(ServerRequest $request): bool
    {
        return $this->identity instanceof Identity;
    }

    public function set(ServerRequest $request, Identity $identity): void
    {
        $this->identity = $identity;
    }
}
