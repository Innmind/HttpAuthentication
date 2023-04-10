<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaBasicAuthorization;

use Innmind\HttpAuthentication\Identity;
use Innmind\Immutable\Maybe;

interface Resolver
{
    /**
     * @return Maybe<Identity>
     */
    public function __invoke(string $user, string $password): Maybe;
}
