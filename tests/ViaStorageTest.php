<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication;

use Innmind\HttpAuthentication\{
    ViaStorage,
    ViaStorage\InMemory,
    Authenticator,
    Identity,
};
use Innmind\Http\Message\ServerRequest;
use PHPUnit\Framework\TestCase;

class ViaStorageTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(
            Authenticator::class,
            new ViaStorage(
                $this->createMock(Authenticator::class),
                new InMemory
            )
        );
    }

    public function testAuthenticateViaInnerAuthenticator()
    {
        $authenticate = new ViaStorage(
            $inner = $this->createMock(Authenticator::class),
            $storage = new InMemory
        );
        $request = $this->createMock(ServerRequest::class);
        $inner
            ->expects($this->once())
            ->method('__invoke')
            ->with($request)
            ->willReturn($identity = $this->createMock(Identity::class));

        $this->assertSame($identity, $authenticate($request));
        $this->assertTrue($storage->has($request));
        // second time is to make sure it uses the storage
        $this->assertSame($identity, $authenticate($request));
    }

    public function testAuthenticateViaStorage()
    {
        $authenticate = new ViaStorage(
            $inner = $this->createMock(Authenticator::class),
            $storage = new InMemory
        );
        $identity = $this->createMock(Identity::class);
        $request = $this->createMock(ServerRequest::class);
        $inner
            ->expects($this->never())
            ->method('__invoke');
        $storage->set($request, $identity);

        $this->assertSame($identity, $authenticate($request));
    }
}
