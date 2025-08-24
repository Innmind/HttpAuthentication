<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaAuthorization;

use Innmind\HttpAuthentication\Identity;
use Innmind\Http\Header\AuthorizationValue;
use Innmind\Immutable\Attempt;

interface Resolver
{
    /**
     * @return Attempt<Identity>
     */
    public function __invoke(AuthorizationValue $value): Attempt;
}
