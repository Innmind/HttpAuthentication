<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\ViaStorage\Storage;
use Innmind\Http\Message\ServerRequest;
use Innmind\Immutable\Maybe;

final class ViaStorage implements Authenticator
{
    private Authenticator $authenticate;
    private Storage $storage;

    public function __construct(Authenticator $authenticate, Storage $storage)
    {
        $this->authenticate = $authenticate;
        $this->storage = $storage;
    }

    public function __invoke(ServerRequest $request): Maybe
    {
        if ($this->storage->contains($request)) {
            return Maybe::just($this->storage->get($request));
        }

        return ($this->authenticate)($request)
            ->map(function($identity) use ($request) {
                $this->storage->set($request, $identity);

                return $identity;
            });
    }
}
