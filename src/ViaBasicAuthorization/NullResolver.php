<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaBasicAuthorization;

use Innmind\HttpAuthentication\Identity;
use Innmind\Immutable\Maybe;

final class NullResolver implements Resolver
{
    public function __invoke(string $user, string $password): Maybe
    {
        /** @var Maybe<Identity> */
        return Maybe::nothing();
    }
}
