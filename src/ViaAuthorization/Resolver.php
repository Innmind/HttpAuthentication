<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaAuthorization;

use Innmind\HttpAuthentication\Identity;
use Innmind\Http\Header\AuthorizationValue;
use Innmind\Immutable\Maybe;

interface Resolver
{
    /**
     * @return Maybe<Identity>
     */
    public function __invoke(AuthorizationValue $value): Maybe;
}
