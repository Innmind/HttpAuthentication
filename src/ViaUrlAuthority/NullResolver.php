<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaUrlAuthority;

use Innmind\HttpAuthentication\{
    Identity,
    Exception\AuthenticatorNotImplemented,
};
use Innmind\Url\Authority\UserInformation\{
    User,
    Password,
};

final class NullResolver implements Resolver
{
    public function __invoke(User $user, Password $password): Identity
    {
        throw new AuthenticatorNotImplemented;
    }
}
