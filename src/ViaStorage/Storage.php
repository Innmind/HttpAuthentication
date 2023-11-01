<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaStorage;

use Innmind\HttpAuthentication\Identity;
use Innmind\Http\ServerRequest;
use Innmind\Immutable\Maybe;

interface Storage
{
    /**
     * @return Maybe<Identity>
     */
    public function get(ServerRequest $request): Maybe;
    public function set(ServerRequest $request, Identity $identity): void;
}
