<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaBasicAuthorization;

use Innmind\HttpAuthentication\Identity;
use Innmind\Immutable\Attempt;

final class NullResolver implements Resolver
{
    public function __invoke(string $user, string $password): Attempt
    {
        /** @var Attempt<Identity> */
        return Attempt::error(new \LogicException('Not implemented'));
    }
}
