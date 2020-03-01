<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaStorage;

use Innmind\HttpAuthentication\Identity;
use Innmind\Http\Message\ServerRequest;

interface Storage
{
    public function get(ServerRequest $request): Identity;
    public function contains(ServerRequest $request): bool;
    public function set(ServerRequest $request, Identity $identity): void;
}
