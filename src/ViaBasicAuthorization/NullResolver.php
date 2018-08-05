<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaBasicAuthorization;

use Innmind\HttpAuthentication\{
    Identity,
    Exception\AuthenticatorNotImplemented,
};

final class NullResolver implements Resolver
{
    public function __invoke(string $user, string $password): Identity
    {
        throw new AuthenticatorNotImplemented;
    }
}
