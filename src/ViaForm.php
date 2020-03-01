<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\{
    ViaForm\Resolver,
    Exception\NotSupported,
};
use Innmind\Http\Message\{
    ServerRequest,
    Method,
};

final class ViaForm implements Authenticator
{
    private Resolver $resolve;

    public function __construct(Resolver $resolve)
    {
        $this->resolve = $resolve;
    }

    public function __invoke(ServerRequest $request): Identity
    {
        if ((string) $request->method() !== Method::POST) {
            throw new NotSupported;
        }

        return ($this->resolve)($request->form());
    }
}
