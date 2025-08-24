<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\ViaStorage\Storage;
use Innmind\Http\ServerRequest;
use Innmind\Immutable\Attempt;

final class ViaStorage implements Authenticator
{
    private Authenticator $authenticate;
    private Storage $storage;

    public function __construct(Authenticator $authenticate, Storage $storage)
    {
        $this->authenticate = $authenticate;
        $this->storage = $storage;
    }

    public function __invoke(ServerRequest $request): Attempt
    {
        return $this
            ->storage
            ->get($request)
            ->attempt(static fn() => new \RuntimeException('Identity not in storage'))
            ->recover(
                fn() => ($this->authenticate)($request)->map(
                    function($identity) use ($request) {
                        $this->storage->set($request, $identity);

                        return $identity;
                    },
                ),
            );
    }
}
