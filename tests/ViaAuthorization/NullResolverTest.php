<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication\ViaAuthorization;

use Innmind\HttpAuthentication\{
    ViaAuthorization\NullResolver,
    ViaAuthorization\Resolver,
    Exception\AuthenticatorNotImplemented,
};
use Innmind\Http\Header\AuthorizationValue;
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

        (new NullResolver)(new AuthorizationValue('Bearer', 'foo'));
    }
}
