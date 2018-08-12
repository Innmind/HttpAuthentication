<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication\ViaStorage;

use Innmind\HttpAuthentication\Identity;

interface Storage
{
    public function get(): Identity;
    public function has(): bool;
    public function set(Identity $identity): void;
}
