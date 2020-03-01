<?php
declare(strict_types = 1);

namespace Innmind\HttpAuthentication;

interface Identity
{
    public function toString(): string;
}
