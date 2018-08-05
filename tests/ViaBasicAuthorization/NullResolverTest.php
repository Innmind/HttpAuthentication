<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication\ViaBasicAuthorization;

use Innmind\HttpAuthentication\{
    ViaBasicAuthorization\NullResolver,
    ViaBasicAuthorization\Resolver,
    Exception\AuthenticatorNotImplemented,
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
        $this->expectException(AuthenticatorNotImplemented::class);

        (new NullResolver)('user', 'password');
    }
}
