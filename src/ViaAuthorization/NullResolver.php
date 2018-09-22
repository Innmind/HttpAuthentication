<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaAuthorization;

use Innmind\HttpAuthentication\{
    Identity,
    Exception\AuthenticatorNotImplemented,
};
use Innmind\Http\Header\AuthorizationValue;

final class NullResolver implements Resolver
{
    public function __invoke(AuthorizationValue $value): Identity
    {
        throw new AuthenticatorNotImplemented;
    }
}
