<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication\ViaStorage;

use Innmind\HttpAuthentication\{
    ViaStorage\InMemory,
    ViaStorage\Storage,
    Identity,
};
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

        (new InMemory)->get();
    }

    public function testHas()
    {
        $storage = new InMemory;

        $this->assertFalse($storage->has());
        $storage->set($this->createMock(Identity::class));
        $this->assertTrue($storage->has());
    }

    public function testSet()
    {
        $storage = new InMemory;
        $identity = $this->createMock(Identity::class);

        $this->assertNull($storage->set($identity));
        $this->assertSame($identity, $storage->get());
    }
}
