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

final class ViaForm
{
    /** @var callable(Form): Attempt<Identity> */
    private $resolve;

    /**
     * @param callable(Form): Attempt<Identity> $resolve
     */
    public function __construct(callable $resolve)
    {
        $this->resolve = $resolve;
    }

    /**
     * @return Attempt<Identity>
     */
    public function __invoke(ServerRequest $request): Attempt
    {
        return Maybe::just($request)
            ->filter(static fn($request) => $request->method() === Method::post)
            ->attempt(static fn() => new \RuntimeException('Failed to resolve identity'))
            ->flatMap(fn($request) => ($this->resolve)($request->form()));
    }
}
