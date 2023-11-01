<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication\ViaStorage;

use Innmind\HttpAuthentication\{
    ViaStorage\NullStorage,
    ViaStorage\Storage,
    Identity,
};
use Innmind\Http\{
    ServerRequest,
    Method,
    ProtocolVersion,
};
use Innmind\Url\Url;
use PHPUnit\Framework\TestCase;

class NullStorageTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(Storage::class, new NullStorage);
    }

    public function testGet()
    {
        $request = ServerRequest::of(
            Url::of('/'),
            Method::get,
            ProtocolVersion::v11,
        );

        $this->assertNull((new NullStorage)->get($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }

    public function testHas()
    {
        $storage = new NullStorage;
        $request = ServerRequest::of(
            Url::of('/'),
            Method::get,
            ProtocolVersion::v11,
        );

        $this->assertFalse($storage->get($request)->match(
            static fn() => true,
            static fn() => false,
        ));
        $storage->set($request, $this->createMock(Identity::class));
        $this->assertFalse($storage->get($request)->match(
            static fn() => true,
            static fn() => false,
        ));
    }

    public function testSet()
    {
        $storage = new NullStorage;
        $request = ServerRequest::of(
            Url::of('/'),
            Method::get,
            ProtocolVersion::v11,
        );

        $this->assertNull($storage->set(
            $request,
            $this->createMock(Identity::class),
        ));

        $this->assertNull($storage->get($request)->match(
            static fn($identity) => $identity,
            static fn() => null,
        ));
    }
}
