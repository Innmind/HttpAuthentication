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
    /** @var callable(Authorization): Attempt<T> */
    private $resolve;

    /**
     * @param callable(Authorization): Attempt<T> $resolve
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
            ->attempt(static fn() => new \RuntimeException('Failed to resolve identity'))
            ->flatMap(fn($value) => ($this->resolve)($value));
    }
}
