<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication\ViaStorage;

use Innmind\HttpAuthentication\{
    ViaStorage\InMemory,
    ViaStorage\Storage,
    Identity,
};
use Innmind\Http\Message\ServerRequest;
use PHPUnit\Framework\TestCase;

class InMemoryTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(Storage::class, new InMemory);
    }

    public function testGet()
    {
        $this->expectException(\TypeError::class);

        (new InMemory)->get($this->createMock(ServerRequest::class));
    }

    public function testHas()
    {
        $storage = new InMemory;
        $request = $this->createMock(ServerRequest::class);

        $this->assertFalse($storage->has($request));
        $storage->set($request, $this->createMock(Identity::class));
        $this->assertTrue($storage->has($request));
    }

    public function testSet()
    {
        $storage = new InMemory;
        $identity = $this->createMock(Identity::class);
        $request = $this->createMock(ServerRequest::class);

        $this->assertNull($storage->set($request, $identity));
        $this->assertSame($identity, $storage->get($request));
    }
}
