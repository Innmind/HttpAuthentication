<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaStorage;

use Innmind\HttpAuthentication\Identity;
use Innmind\Http\Message\ServerRequest;
use Innmind\Immutable\{
    Map,
    Maybe,
};

final class InMemory implements Storage
{
    /** @var Map<ServerRequest, Identity> */
    private Map $identities;

    public function __construct()
    {
        $this->identities = Map::of();
    }

    public function get(ServerRequest $request): Maybe
    {
        return $this->identities->get($request);
    }

    public function set(ServerRequest $request, Identity $identity): void
    {
        $this->identities = ($this->identities)($request, $identity);
    }
}
