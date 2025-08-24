<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\Http\{
    ServerRequest,
    Header\Authorization,
};
use Innmind\Immutable\Attempt;

final class ValidateAuthorizationHeader implements Authenticator
{
    private Authenticator $authenticate;

    public function __construct(Authenticator $authenticate)
    {
        $this->authenticate = $authenticate;
    }

    public function __invoke(ServerRequest $request): Attempt
    {
        if (!$request->headers()->contains('Authorization')) {
            return ($this->authenticate)($request);
        }

        return $request
            ->headers()
            ->find(Authorization::class)
            ->attempt(static fn() => new \RuntimeException('No Authorization header'))
            ->flatMap(fn() => ($this->authenticate)($request));
    }
}
