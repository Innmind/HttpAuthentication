<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication\ViaAuthorization;

use Innmind\HttpAuthentication\{
    ViaAuthorization\NullResolver,
    ViaAuthorization\Resolver,
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
        $this->assertNull((new NullResolver)(new AuthorizationValue('Bearer', 'foo'))->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }
}
