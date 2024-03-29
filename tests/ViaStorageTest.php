<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\{
    ViaStorage,
    ViaStorage\InMemory,
    Authenticator,
    Identity,
};
use Innmind\Http\{
    ServerRequest,
    Method,
    ProtocolVersion,
};
use Innmind\Url\Url;
use Innmind\Immutable\Maybe;
use PHPUnit\Framework\TestCase;

class ViaStorageTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Authenticator::class,
            new ViaStorage(
                $this->createMock(Authenticator::class),
                new InMemory,
            ),
        );
    }

    public function testAuthenticateViaInnerAuthenticator()
    {
        $authenticate = new ViaStorage(
            $inner = $this->createMock(Authenticator::class),
            $storage = new InMemory,
        );
        $request = ServerRequest::of(
            Url::of('/'),
            Method::get,
            ProtocolVersion::v11,
        );
        $inner
            ->expects($this->once())
            ->method('__invoke')
            ->with($request)
            ->willReturn(Maybe::just($identity = $this->createMock(Identity::class)));

        $this->assertSame($identity, $authenticate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
        $this->assertTrue($storage->get($request)->match(
            static fn() => true,
            static fn() => false,
        ));
        // second time is to make sure it uses the storage
        $this->assertSame($identity, $authenticate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }

    public function testAuthenticateViaStorage()
    {
        $authenticate = new ViaStorage(
            $inner = $this->createMock(Authenticator::class),
            $storage = new InMemory,
        );
        $identity = $this->createMock(Identity::class);
        $request = ServerRequest::of(
            Url::of('/'),
            Method::get,
            ProtocolVersion::v11,
        );
        $inner
            ->expects($this->never())
            ->method('__invoke');
        $storage->set($request, $identity);

        $this->assertSame($identity, $authenticate($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }
}
