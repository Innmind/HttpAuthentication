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
    /** @var callable(Form): Attempt<T> */
    private $resolve;

    /**
     * @param callable(Form): Attempt<T> $resolve
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
        return Maybe::just($request)
            ->filter(static fn($request) => $request->method() === Method::post)
            ->attempt(static fn() => new \RuntimeException('Failed to resolve identity'))
            ->flatMap(fn($request) => ($this->resolve)($request->form()));
    }
}
