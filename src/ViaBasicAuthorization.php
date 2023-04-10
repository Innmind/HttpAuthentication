<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\{
    ViaBasicAuthorization\Resolver,
    Exception\NotSupported,
};
use Innmind\Http\{
    Message\ServerRequest,
    Header\Authorization,
};

final class ViaBasicAuthorization implements Authenticator
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
            ->filter(static fn($header) => $header->scheme() === 'Basic')
            ->map(function($header) {
                [$user, $password] = \explode(':', \base64_decode($header->parameter(), true));

                return ($this->resolve)($user, $password);
            })
            ->match(
                static fn($identity) => $identity,
                static fn() => throw new NotSupported,
            );
    }
}
