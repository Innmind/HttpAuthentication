<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaUrlAuthority;

use Innmind\HttpAuthentication\Identity;
use Innmind\Url\Authority\UserInformation\{
    User,
    Password,
};
use Innmind\Immutable\Attempt;

final class NullResolver implements Resolver
{
    public function __invoke(User $user, Password $password): Attempt
    {
        /** @var Attempt<Identity> */
        return Attempt::error(new \LogicException('Not implemented'));
    }
}
