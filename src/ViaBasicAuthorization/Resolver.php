<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaBasicAuthorization;

use Innmind\HttpAuthentication\Identity;

interface Resolver
{
    public function __invoke(string $user, string $password): Identity;
}
