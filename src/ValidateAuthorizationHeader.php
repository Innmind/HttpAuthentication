<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\Http\{
    ServerRequest,
    Header\Authorization,
};
use Innmind\Immutable\Maybe;

final class ValidateAuthorizationHeader implements Authenticator
{
    private Authenticator $authenticate;

    public function __construct(Authenticator $authenticate)
    {
        $this->authenticate = $authenticate;
    }

    public function __invoke(ServerRequest $request): Maybe
    {
        if (!$request->headers()->contains('Authorization')) {
            return ($this->authenticate)($request);
        }

        return $request
            ->headers()
            ->find(Authorization::class)
            ->flatMap(fn() => ($this->authenticate)($request));
    }
}
