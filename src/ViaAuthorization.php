<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\Http\{
    ServerRequest,
    Header\Authorization,
    Header\AuthorizationValue,
};
use Innmind\Immutable\{
    Attempt,
    Predicate\Instance,
};

/**
 * @template T
 */
final class ViaAuthorization
{
    /** @var callable(AuthorizationValue): Attempt<T> */
    private $resolve;

    /**
     * @param callable(AuthorizationValue): Attempt<T> $resolve
     */
    public function __construct(callable $resolve)
    {
        $this->resolve = $resolve;
    }

    /**
     * @return Attempt<T>
     */
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
