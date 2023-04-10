<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication\ViaBasicAuthorization;

use Innmind\HttpAuthentication\{
    ViaBasicAuthorization\NullResolver,
    ViaBasicAuthorization\Resolver,
};
use PHPUnit\Framework\TestCase;

class NullResolverTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(Resolver::class, new NullResolver);
    }

    public function testInvokation()
    {
        $this->assertNull((new NullResolver)('user', 'password')->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }
}
