<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\Http\{
    ServerRequest,
    ServerRequest\Form,
    Method,
};
use Innmind\Immutable\{
    Maybe,
    Attempt,
};

/**
 * @template T
 */
final class ViaForm
{
    /**
     * @param \Closure(Form): Attempt<T> $resolve
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
        return Maybe::just($request)
            ->filter(static fn($request) => $request->method() === Method::post)
            ->attempt(static fn() => new \RuntimeException('Failed to resolve identity'))
            ->flatMap(fn($request) => ($this->resolve)($request->form()));
    }

    /**
     * @template A
     *
     * @param callable(Form): Attempt<A> $resolve
     *
     * @return self<A>
     */
    #[\NoDiscard]
    public static function of(callable $resolve): self
    {
        return new self(\Closure::fromCallable($resolve));
    }
}
