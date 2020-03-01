<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\Exception\MalformedAuthorizationHeader;
use Innmind\Http\{
    Message\ServerRequest,
    Header\Authorization,
};

final class ValidateAuthorizationHeader implements Authenticator
{
    private Authenticator $authenticate;

    public function __construct(Authenticator $authenticate)
    {
        $this->authenticate = $authenticate;
    }

    public function __invoke(ServerRequest $request): Identity
    {
        if (!$request->headers()->has('Authorization')) {
            return ($this->authenticate)($request);
        }

        $header = $request->headers()->get('Authorization');

        if (!$header instanceof Authorization) {
            throw new MalformedAuthorizationHeader;
        }

        return ($this->authenticate)($request);
    }
}
