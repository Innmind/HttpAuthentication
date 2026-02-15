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
    /**
     * @param \Closure(string, string): Attempt<T> $resolve
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
            ->filter(static fn($header) => $header->scheme() === 'Basic')
            ->attempt(static fn() => new \RuntimeException('Failed to resolve identity'))
            ->flatMap(function($header) {
                $string = \base64_decode($header->parameter(), true);

                if (!\is_string($string)) {
                    return Attempt::error(new \RuntimeException('Malformed authorization header parameter'));
                }

                [$user, $password] = \explode(':', $string);

                return ($this->resolve)($user, $password);
            });
    }

    /**
     * @template A
     *
     * @param callable(string, string): Attempt<A> $resolve
     *
     * @return self<A>
     */
    #[\NoDiscard]
    public static function of(callable $resolve): self
    {
        return new self(\Closure::fromCallable($resolve));
    }
}
