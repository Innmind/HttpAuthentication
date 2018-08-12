<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication\ViaStorage;

use Innmind\HttpAuthentication\{
    ViaStorage\NullStorage,
    ViaStorage\Storage,
    Identity,
};
use Innmind\Http\Message\ServerRequest;
use PHPUnit\Framework\TestCase;

class NullStorageTest extends TestCase
{
    public function testInterface()
    {
        $this->assertInstanceOf(Storage::class, new NullStorage);
    }

    public function testGet()
    {
        $this->expectException(\TypeError::class);

        (new NullStorage)->get($this->createMock(ServerRequest::class));
    }

    public function testHas()
    {
        $storage = new NullStorage;
        $request = $this->createMock(ServerRequest::class);

        $this->assertFalse($storage->has($request));
        $storage->set($this->createMock(Identity::class));
        $this->assertFalse($storage->has($request));
    }

    public function testSet()
    {
        $storage = new NullStorage;

        $this->assertNull($storage->set($this->createMock(Identity::class)));

        $this->expectException(\TypeError::class);

        $storage->get($this->createMock(ServerRequest::class));
    }
}
