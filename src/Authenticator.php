<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\Http\ServerRequest;
use Innmind\Immutable\Attempt;

interface Authenticator
{
    /**
     * @return Attempt<Identity>
     */
    public function __invoke(ServerRequest $request): Attempt;
}
