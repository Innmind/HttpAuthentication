<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\{
    Any,
    Authenticator,
    Identity,
};
use Innmind\Http\Message\ServerRequest;
use Innmind\Immutable\Maybe;
use PHPUnit\Framework\TestCase;

class AnyTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(Authenticator::class, new Any);
    }

    public function testReturnNothingWhenNoAuthenticationProvided()
    {
        $this->assertNull((new Any)($this->createMock(ServerRequest::class))->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }

    public function testInvokation()
    {
        $authenticate = new Any(
            $notSupported = $this->createMock(Authenticator::class),
            $notImplemented = $this->createMock(Authenticator::class),
            $expected = $this->createMock(Authenticator::class),
            $notCalled = $this->createMock(Authenticator::class),
        );
        $request = $this->createMock(ServerRequest::class);
        $notSupported
            ->expects($this->once())
            ->method('__invoke')
            ->with($request)
            ->willReturn(Maybe::nothing());
        $notImplemented
            ->expects($this->once())
            ->method('__invoke')
            ->with($request)
            ->willReturn(Maybe::nothing());
        $expected
            ->expects($this->once())
            ->method('__invoke')
            ->with($request)
            ->willReturn(Maybe::just($identity = $this->createMock(Identity::class)));
        $notCalled
            ->expects($this->never())
            ->method('__invoke');

        $this->assertSame($identity, $authenticate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }
}
