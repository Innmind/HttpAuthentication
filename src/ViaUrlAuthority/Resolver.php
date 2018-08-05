<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaUrlAuthority;

use Innmind\HttpAuthentication\Identity;
use Innmind\Url\Authority\UserInformation\{
    UserInterface,
    PasswordInterface,
};

interface Resolver
{
    public function __invoke(UserInterface $user, PasswordInterface $password): Identity;
}
