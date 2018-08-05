<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\Http\Message\ServerRequest;

interface Authenticator
{
    public function __invoke(ServerRequest $request): Identity;
}
