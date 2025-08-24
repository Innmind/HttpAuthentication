<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\ViaBasicAuthorization\Resolver;
use Innmind\Http\{
    ServerRequest,
    Header\Authorization,
};
use Innmind\Immutable\Attempt;

final class ViaBasicAuthorization implements Authenticator
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
            ->filter(static fn($header) => $header->scheme() === 'Basic')
            ->attempt(static fn() => new \RuntimeException('Failed to resolve identity'))
            ->flatMap(function($header) {
                [$user, $password] = \explode(':', \base64_decode($header->parameter(), true));

                return ($this->resolve)($user, $password);
            });
    }
}
