<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaAuthorization;

use Innmind\HttpAuthentication\Identity;
use Innmind\Http\Header\AuthorizationValue;
use Innmind\Immutable\Maybe;

final class NullResolver implements Resolver
{
    public function __invoke(AuthorizationValue $value): Maybe
    {
        /** @var Maybe<Identity> */
        return Maybe::nothing();
    }
}
