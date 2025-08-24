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
final class ViaBasicAuthorization
{
    /** @var callable(string, string): Attempt<T> */
    private $resolve;

    /**
     * @param callable(string, string): Attempt<T> $resolve
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
            ->filter(static fn($header) => $header->scheme() === 'Basic')
            ->attempt(static fn() => new \RuntimeException('Failed to resolve identity'))
            ->flatMap(function($header) {
                [$user, $password] = \explode(':', \base64_decode($header->parameter(), true));

                return ($this->resolve)($user, $password);
            });
    }
}
