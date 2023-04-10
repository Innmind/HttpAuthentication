<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\{
    ViaAuthorization\Resolver,
    Exception\NotSupported,
};
use Innmind\Http\{
    Message\ServerRequest,
    Header\Authorization,
    Header\AuthorizationValue,
};
use Innmind\Immutable\Predicate\Instance;

final class ViaAuthorization implements Authenticator
{
    private Resolver $resolve;

    public function __construct(Resolver $resolve)
    {
        $this->resolve = $resolve;
    }

    public function __invoke(ServerRequest $request): Identity
    {
        return $request
            ->headers()
            ->find(Authorization::class)
            ->flatMap(static fn($header) => $header->values()->find(static fn() => true))
            ->keep(Instance::of(AuthorizationValue::class))
            ->map(fn($value) => ($this->resolve)($value))
            ->match(
                static fn($identity) => $identity,
                static fn() => throw new NotSupported,
            );
    }
}
