<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\ViaForm\Resolver;
use Innmind\Http\{
    ServerRequest,
    Method,
};
use Innmind\Immutable\{
    Maybe,
    Attempt,
};

final class ViaForm implements Authenticator
{
    private Resolver $resolve;

    public function __construct(Resolver $resolve)
    {
        $this->resolve = $resolve;
    }

    public function __invoke(ServerRequest $request): Attempt
    {
        return Maybe::just($request)
            ->filter(static fn($request) => $request->method() === Method::post)
            ->attempt(static fn() => new \RuntimeException('Failed to resolve identity'))
            ->flatMap(fn($request) => ($this->resolve)($request->form()));
    }
}
