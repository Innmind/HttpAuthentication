<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\ViaForm\Resolver;
use Innmind\Http\{
    ServerRequest,
    Method,
};
use Innmind\Immutable\Maybe;

final class ViaForm implements Authenticator
{
    private Resolver $resolve;

    public function __construct(Resolver $resolve)
    {
        $this->resolve = $resolve;
    }

    public function __invoke(ServerRequest $request): Maybe
    {
        return Maybe::just($request)
            ->filter(static fn($request) => $request->method() === Method::post)
            ->flatMap(fn($request) => ($this->resolve)($request->form()));
    }
}
