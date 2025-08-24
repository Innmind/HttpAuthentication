<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaBasicAuthorization;

use Innmind\HttpAuthentication\Identity;
use Innmind\Immutable\Attempt;

interface Resolver
{
    /**
     * @return Attempt<Identity>
     */
    public function __invoke(string $user, string $password): Attempt;
}
