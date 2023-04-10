<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\{
    Any,
    Authenticator,
    Identity,
    Exception\NotSupported,
    Exception\AuthenticatorNotImplemented,
    Exception\NoAuthenticationProvided,
};
use Innmind\Http\Message\ServerRequest;
use PHPUnit\Framework\TestCase;

class AnyTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(Authenticator::class, new Any);
    }

    public function testThrowWhenNoAuthenticationProvided()
    {
        $this->expectException(NoAuthenticationProvided::class);

        (new Any)($this->createMock(ServerRequest::class));
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
            ->will($this->throwException(new NotSupported));
        $notImplemented
            ->expects($this->once())
            ->method('__invoke')
            ->with($request)
            ->will($this->throwException(new AuthenticatorNotImplemented));
        $expected
            ->expects($this->once())
            ->method('__invoke')
            ->with($request)
            ->willReturn($identity = $this->createMock(Identity::class));
        $notCalled
            ->expects($this->never())
            ->method('__invoke');

        $this->assertSame($identity, $authenticate($request));
    }
}
