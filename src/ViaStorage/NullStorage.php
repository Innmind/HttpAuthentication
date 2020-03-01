<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaStorage;

use Innmind\HttpAuthentication\Identity;
use Innmind\Http\Message\ServerRequest;

final class NullStorage implements Storage
{
    /** @psalm-suppress InvalidReturnType */
    public function get(ServerRequest $request): Identity
    {
        // let throw a TypeError
    }

    public function contains(ServerRequest $request): bool
    {
        return false;
    }

    public function set(ServerRequest $request, Identity $identity): void
    {
        // pass
    }
}
