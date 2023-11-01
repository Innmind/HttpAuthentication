<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\Http\ServerRequest;
use Innmind\Immutable\Maybe;

interface Authenticator
{
    /**
     * @return Maybe<Identity>
     */
    public function __invoke(ServerRequest $request): Maybe;
}
