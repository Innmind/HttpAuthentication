<?php
declare(strict_types = 1);

namespace Tests\Innmind\HttpAuthentication\ViaStorage;

use Innmind\HttpAuthentication\{
    ViaStorage\NullStorage,
    ViaStorage\Storage,
    Identity,
};
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

        (new NullStorage)->get();
    }

    public function testHas()
    {
        $storage = new NullStorage;

        $this->assertFalse($storage->has());
        $storage->set($this->createMock(Identity::class));
        $this->assertFalse($storage->has());
    }

    public function testSet()
    {
        $storage = new NullStorage;

        $this->assertNull($storage->set($this->createMock(Identity::class)));

        $this->expectException(\TypeError::class);

        $storage->get();
    }
}
