<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\ViaStorage\Storage;
use Innmind\Http\Message\ServerRequest;

final class ViaStorage implements Authenticator
{
    private Authenticator $authenticate;
    private Storage $storage;

    public function __construct(Authenticator $authenticate, Storage $storage)
    {
        $this->authenticate = $authenticate;
        $this->storage = $storage;
    }

    public function __invoke(ServerRequest $request): Identity
    {
        if ($this->storage->contains($request)) {
            return $this->storage->get($request);
        }

        $identity = ($this->authenticate)($request);
        $this->storage->set($request, $identity);

        return $identity;
    }
}
