<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\Http\{
    ServerRequest,
    Header\Authorization,
};
use Innmind\Immutable\Attempt;

/**
 * @template T
 */
final class ViaAuthorization
{
    /**
     * @param \Closure(Authorization): Attempt<T> $resolve
     */
    private function __construct(private \Closure $resolve)
    {
    }

    /**
     * @return Attempt<T>
     */
    #[\NoDiscard]
    public function __invoke(ServerRequest $request): Attempt
    {
        return $request
            ->headers()
            ->find(Authorization::class)
            ->attempt(static fn() => new \RuntimeException('Failed to resolve identity'))
            ->flatMap(fn($value) => ($this->resolve)($value));
    }

    /**
     * @template A
     *
     * @param callable(Authorization): Attempt<A> $resolve
     *
     * @return self<A>
     */
    #[\NoDiscard]
    public static function of(callable $resolve): self
    {
        return new self(\Closure::fromCallable($resolve));
    }
}
