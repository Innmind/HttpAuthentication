<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaAuthorization;

use Innmind\HttpAuthentication\Identity;
use Innmind\Http\Header\AuthorizationValue;

interface Resolver
{
    public function __invoke(AuthorizationValue $value): Identity;
}
