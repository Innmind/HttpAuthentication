<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaAuthorization;

use Innmind\HttpAuthentication\Identity;
use Innmind\Http\Header\AuthorizationValue;
use Innmind\Immutable\Attempt;

final class NullResolver implements Resolver
{
    public function __invoke(AuthorizationValue $value): Attempt
    {
        /** @var Attempt<Identity> */
        return Attempt::error(new \LogicException('Not implemented'));
    }
}
