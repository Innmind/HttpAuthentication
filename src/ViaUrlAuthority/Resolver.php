<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaUrlAuthority;

use Innmind\HttpAuthentication\Identity;
use Innmind\Url\Authority\UserInformation\{
    User,
    Password,
};
use Innmind\Immutable\Attempt;

interface Resolver
{
    /**
     * @return Attempt<Identity>
     */
    public function __invoke(User $user, Password $password): Attempt;
}
