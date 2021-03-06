<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\{
    ViaAuthorization\Resolver,
    Exception\NotSupported,
};
use Innmind\Http\{
    Message\ServerRequest,
    Header\Authorization,
};
use function Innmind\Immutable\first;

final class ViaAuthorization implements Authenticator
{
    private Resolver $resolve;

    public function __construct(Resolver $resolve)
    {
        $this->resolve = $resolve;
    }

    public function __invoke(ServerRequest $request): Identity
    {
        if (!$request->headers()->contains('Authorization')) {
            throw new NotSupported;
        }

        $header = $request->headers()->get('Authorization');

        /** @psalm-suppress RedundantCondition */
        if (!$header instanceof Authorization) {
            // because it should mean the value doesn't respect the standard
            throw new NotSupported;
        }

        $value = first($header->values());

        return ($this->resolve)($value);
    }
}
