<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaStorage;

use Innmind\HttpAuthentication\Identity;
use Innmind\Http\Message\ServerRequest;
use Innmind\Immutable\Maybe;

final class NullStorage implements Storage
{
    public function get(ServerRequest $request): Maybe
    {
        /** @var Maybe<Identity> */
        return Maybe::nothing();
    }

    public function set(ServerRequest $request, Identity $identity): void
    {
        // pass
    }
}
