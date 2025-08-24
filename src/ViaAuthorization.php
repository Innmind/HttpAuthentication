<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\ViaAuthorization\Resolver;
use Innmind\Http\{
    ServerRequest,
    Header\Authorization,
    Header\AuthorizationValue,
};
use Innmind\Immutable\{
    Attempt,
    Predicate\Instance,
};

final class ViaAuthorization implements Authenticator
{
    private Resolver $resolve;

    public function __construct(Resolver $resolve)
    {
        $this->resolve = $resolve;
    }

    public function __invoke(ServerRequest $request): Attempt
    {
        return $request
            ->headers()
            ->find(Authorization::class)
            ->flatMap(static fn($header) => $header->values()->find(static fn() => true))
            ->keep(Instance::of(AuthorizationValue::class))
            ->attempt(static fn() => new \RuntimeException('Failed to resolve identity'))
            ->flatMap(fn($value) => ($this->resolve)($value));
    }
}
