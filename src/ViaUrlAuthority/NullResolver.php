<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaUrlAuthority;

use Innmind\HttpAuthentication\Identity;
use Innmind\Url\Authority\UserInformation\{
    User,
    Password,
};
use Innmind\Immutable\Maybe;

final class NullResolver implements Resolver
{
    public function __invoke(User $user, Password $password): Maybe
    {
        /** @var Maybe<Identity> */
        return Maybe::nothing();
    }
}
