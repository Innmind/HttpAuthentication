<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaUrlAuthority;

use Innmind\HttpAuthentication\{
    Identity,
    Exception\AuthenticatorNotImplemented,
};
use Innmind\Url\Authority\UserInformation\{
    UserInterface,
    PasswordInterface,
};

final class NullResolver implements Resolver
{
    public function __invoke(UserInterface $user, PasswordInterface $password): Identity
    {
        throw new AuthenticatorNotImplemented;
    }
}
